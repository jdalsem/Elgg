<?php
/**
 * Elgg user settings functions.
 * Functions for adding and manipulating options on the user settings panel.
 */

use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

/**
 * Set a user's password
 * Returns null if no change is required
 * Returns true or false indicating success or failure if change was needed
 *
 * @elgg_plugin_hook usersettings:save user
 *
 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
 *
 * @return bool|null|void
 * @since 1.8.0
 * @internal
 */
function _elgg_set_user_password(\Elgg\Hook $hook) {

	$actor = elgg_get_logged_in_user_entity();
	if (!$actor instanceof ElggUser) {
		return;
	}

	$user = $hook->getUserParam();
	$request = $hook->getParam('request');
	
	if (!$user instanceof ElggUser || !$request instanceof Request) {
		return;
	}

	$password = $request->getParam('password', null, false);
	$password2 = $request->getParam('password2', null, false);

	if (!$password) {
		return null;
	}

	if (!$actor->isAdmin() || $user->guid === $actor->guid) {
		// let admin user change anyone's password without knowing it except his own.

		$current_password = $request->getParam('current_password', null, false);

		try {
			elgg()->accounts->assertCurrentPassword($user, $current_password);
		} catch (RegistrationException $e) {
			$request->validation()->fail('password', '', elgg_echo('LoginException:ChangePasswordFailure'));

			return false;
		}
	}

	try {
		elgg()->accounts->assertValidPassword([$password, $password2]);
	} catch (RegistrationException $e) {
		$request->validation()->fail('password', '', $e->getMessage());

		return false;
	}

	$user->setPassword($password);
	_elgg_services()->persistentLogin->handlePasswordChange($user, $actor);
	
	if (elgg_get_config('security_notify_user_password')) {
		// notify the user that their password has changed
		$site = elgg_get_site_entity();
		
		$subject = elgg_echo('user:notification:password_change:subject', [], $user->language);
		$body = elgg_echo('user:notification:password_change:body', [
			$user->getDisplayName(),
			$site->getDisplayName(),
			elgg_generate_url('account:password:reset'),
			$site->getURL(),
		], $user->language);
		
		$params = [
			'object' => $user,
			'action' => 'password_change',
		];
		
		notify_user($user->guid, $site->guid, $subject, $body, $params, ['email']);
	}

	$request->validation()->pass('password', '', elgg_echo('user:password:success'));
}

/**
 * Set a user's display name
 * Returns null if no change is required or input is not present in the form
 * Returns true or false indicating success or failure if change was needed
 *
 * @elgg_plugin_hook usersettings:save user
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return bool|null
 * @since 1.8.0
 * @internal
 */
function _elgg_set_user_name(\Elgg\Hook $hook) {

	$user = $hook->getUserParam();
	$request = $hook->getParam('request');
	/* @var $request \Elgg\Request */

	$name = $request->getParam('name');
	if (!isset($name)) {
		return null;
	}

	$name = strip_tags($name);
	if (empty($name)) {
		$request->validation()->fail('name', $request->getParam('name'), elgg_echo('user:name:fail'));

		return false;
	}

	if ($name === $user->name) {
		return null;
	}

	$request->validation()->pass('name', $name, elgg_echo('user:name:success'));

	$user->name = $name;

}

/**
 * Set a user's username
 * Returns null if no change is required or input is not present in the form
 * Returns true or false indicating success or failure if change was needed
 *
 * @elgg_plugin_hook usersettings:save user
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return bool|null
 *
 * @since 3.0
 * @internal
 */
function _elgg_set_user_username(\Elgg\Hook $hook) {

	$user = $hook->getUserParam();
	$request = $hook->getParam('request');
	
	if (!$user instanceof ElggUser || !$request instanceof Request) {
		return null;
	}

	$username = $request->getParam('username');
	if (!isset($username)) {
		return null;
	}

	if (!elgg_is_admin_logged_in() && !elgg_get_config('can_change_username', false)) {
		return null;
	}
	
	if (!$user->canEdit()) {
		return null;
	}

	if ($user->username === $username) {
		return null;
	}

	// check if username is valid and does not exist
	try {
		elgg()->accounts->assertValidUsername($username, true);
	} catch (RegistrationException $ex) {
		$request->validation()->fail('username', $username, $ex->getMessage());

		return false;
	}

	$user->username = $username;

	$request->validation()->pass('username', $username, elgg_echo('user:username:success'));

	// correctly forward after after a username change
	elgg_register_plugin_hook_handler('response', 'action:usersettings/save', function (\Elgg\Hook $hook) use ($username) {
		$response = $hook->getValue();
		if (!$response instanceof ResponseBuilder) {
			return;
		}

		if ($response->getForwardURL() === REFERRER) {
			$response->setForwardURL(elgg_generate_url('settings:account', [
				'username' => $username,
			]));
		}

		return $response;
	});
}

/**
 * Set a user's language
 * Returns null if no change is required or input is not present in the form
 * Returns true or false indicating success or failure if change was needed
 *
 * @elgg_plugin_hook usersettings:save user
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return bool|null
 * @since 1.8.0
 * @internal
 */
function _elgg_set_user_language(\Elgg\Hook $hook) {

	$user = $hook->getUserParam();
	$request = $hook->getParam('request');
	
	if (!$user instanceof ElggUser || !$request instanceof Request) {
		return null;
	}

	$language = $request->getParam('language');
	if (!isset($language)) {
		return null;
	}

	if ($language === $user->language) {
		return null;
	}
	
	if (!in_array($language, elgg()->translator->getAllowedLanguages())) {
		return null;
	}

	$user->language = $language;

	$request->validation()->pass('language', $language, elgg_echo('user:language:success'));
}

/**
 * Set a user's email address
 * Returns null if no change is required or input is not present in the form
 * Returns true or false indicating success or failure if change was needed
 *
 * @elgg_plugin_hook usersettings:save user
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return bool|null
 * @since 1.8.0
 * @internal
 */
function _elgg_set_user_email(\Elgg\Hook $hook) {
	
	$actor = elgg_get_logged_in_user_entity();
	if (!$actor instanceof ElggUser) {
		return null;
	}

	$user = $hook->getUserParam();
	$request = $hook->getParam('request');
	
	if (!$user instanceof ElggUser || !$request instanceof Request) {
		return null;
	}

	$email = $request->getParam('email');
	if (!isset($email)) {
		return null;
	}

	if (strcmp($email, $user->email) === 0) {
		// no change
		return null;
	}

	try {
		elgg()->accounts->assertValidEmail($email, true);
	} catch (RegistrationException $ex) {
		$request->validation()->fail('email', $email, $ex->getMessage());

		return false;
	}

	if (elgg()->config->security_email_require_password && $user->guid === $actor->guid) {
		try {
			// validate password
			elgg()->accounts->assertCurrentPassword($user, $request->getParam('email_password'));
		} catch (RegistrationException $e) {
			$request->validation()->fail('email', $email, elgg_echo('email:save:fail:password'));
			return false;
		}
	}

	$hook_params = $hook->getParams();
	$hook_params['email'] = $email;

	if (!elgg_trigger_plugin_hook('change:email', 'user', $hook_params, true)) {
		return null;
	}
	
	if (elgg()->config->security_email_require_confirmation) {
		// validate the new email address
		try {
			elgg()->accounts->requestNewEmailValidation($user, $email);
			
			$request->validation()->pass('email', $email, elgg_echo('account:email:request:success', [$email]));
			return true;
		} catch (InvalidParameterException $e) {
			$request->validation()->fail('email', $email, elgg_echo('email:save:fail:password'));
			return false;
		}
	}
	
	$user->email = $email;
	$request->validation()->pass('email', $email, elgg_echo('email:save:success'));
}

/**
 * Set a user's default access level
 * Returns null if no change is required or input is not present in the form
 * Returns true or false indicating success or failure if change was needed
 *
 * @elgg_plugin_hook usersettings:save user
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return bool|null
 *
 * @since 1.8.0
 * @internal
 */
function _elgg_set_user_default_access(\Elgg\Hook $hook) {

	if (!elgg()->config->allow_user_default_access) {
		return null;
	}

	$user = $hook->getUserParam();
	$request = $hook->getParam('request');
	/* @var $request \Elgg\Request */

	$default_access = $request->getParam('default_access');
	if (!isset($default_access)) {
		return null;
	}

	if ($user->setPrivateSetting('elgg_default_access', $default_access)) {
		$request->validation()->pass('default_access', $default_access, elgg_echo('user:default_access:success'));
	} else {
		$request->validation()->fail('default_access', $default_access, elgg_echo(elgg_echo('user:default_access:failure')));
	}
}
