define(['elgg/Ajax'], function(Ajax) {
	
	function getFeedItems(queryText) {
		// As an example of an asynchronous action, return a promise
		// that resolves after a 100ms timeout.
		// This can be a server request or any sort of delayed action.
		return new Promise(resolve => {
			setTimeout(() => {
				var ajax = new Ajax(false);
		
				var result = ajax.path('livesearch/users', {
					data: {
						term: queryText,
						name: 'members'
					},
					success: function(data) {
						data.forEach(function(item, index, arr) {
							item.id = '@' + item.username;
						});
					}
				});
	
				resolve(result);
			}, 100);
		});
	}
	
	function customItemRenderer( item ) {
		const itemElement = document.createElement('span');
		itemElement.classList.add('custom-item');
			
		//itemElement.id = `mention-list-item-id-${ item.userId }`;

		const textElement = document.createElement('span');
		textElement.textContent = `${item.name}`;
		
		const iconElement = document.createElement('span');
		iconElement.innerHTML = item.icon;

		const usernameElement = document.createElement('span');
		usernameElement.classList.add('custom-item-username');
		usernameElement.textContent = '@' + item.username;
			
		itemElement.appendChild(iconElement);
		itemElement.appendChild(textElement);
		itemElement.appendChild(usernameElement);
		
		return itemElement;
	}
	
	return {
		mention: {
			feeds: [{
				marker: '@',
				feed: getFeedItems,
				itemRenderer: customItemRenderer,
				minimumCharacters: 1
			}]
		}
	};
});
