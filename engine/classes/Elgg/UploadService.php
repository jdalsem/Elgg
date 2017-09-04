<?php

namespace Elgg;

use Elgg\Http\Request;
use ElggFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 * @since 2.3
 */
class UploadService {

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * Constructor
	 *
	 * @param Request $request Http request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}

	/**
	 * Returns an array of uploaded file objects regardless of upload status/errors
	 *
	 * @param string $input_name Form input name
	 * @return UploadedFile[]|false
	 */
	public function getUploadedFiles($input_name) {
		$file_bag = $this->request->files;
		if (!$file_bag->has($input_name)) {
			return false;
		}

		$files = $file_bag->get($input_name);
		if (!$files) {
			return [];
		}
		if (!is_array($files)) {
			$files = [$files];
		}
		
		array_walk($files, self::fixImageOrientation());
		
		return $files;
	}

	/**
	 * Fix orientation for images
	 *
	 * @param UploadedFile $file Uploaded file
	 *
	 * @return void
	 */
	protected function fixImageOrientation($file) {
		
		if (!$file->isValid()) {
			return;
		}
		
		$allowed_extensions = [
			'gif',
			'jpeg',
			'jpg',
			'png',
			'wbmp',
			'xbm',
		];
		if ($file->getExtension())
		
		$file_name = $file->getFilename();
		
		$imagine = new Imagine();
		$imagine->setMetadataReader(new ExifMetadataReader());
		$autorotate = new Autorotate();
	
		$autorotate->apply($imagine->open($file_name))->save($file_name);
	}
}
