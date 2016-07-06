jQuery(function($) {
	jQuery('body').on('click','.del-btn',function(){
		var $this=$(this),$td=$(this).closest('tr').find('td:first');
		if(confirm('Удалить запись №'+$td.text()+'?')){
			$.ajax({
				type: 'post',
				url: $this.attr('href'),
				beforeSend: function(){
					$('#gridview').attr('opacity', '0.5');
				},
				success: function (data) {
					$('#gridview').attr('opacity', '1');

					if(data=='true'){
						$('#gridview').load('/messages/search #gridview > *');
					}else{
						$('#flash-messages').html($(data).find('#flash-messages').html());
					}
				},
			});
		}

		return false;
	});

	jQuery('body').on('submit','#message-form',function(){
		var $this=$(this);

		$.ajax({
			type: 'post',
			url: $this.attr('action'),
			data: $this.serialize(),
			beforeSend: function(){
				$this.attr('opacity', '0.5');
			},
			success: function (data) {
				$this.attr('opacity', '1');

				if(data=='true'){
					$('#messageModal').modal('hide');
					$('#gridview').load('/messages/search #gridview > *');
				}else{
				$('#message-form').html($(data).html());
			}
			},
		});

		return false;
	});

	jQuery('body').on('click','.create-msg',function(){
		var $this=$(this);

		$('#messageModal .modal-title').text('Добавление');
		$('#messageModal').modal('show');
		$('#messageModal .modal-content').load($this.attr('href')+' > *');
		return false;
	});

	jQuery('body').on('click','.edit-msg',function(){
		var $this=$(this);

		$('#messageModal .modal-title').text('Редактирование');
		$('#messageModal').modal('show');
		$('#messageModal .modal-content').load($this.attr('href')+' > *');
		return false;
	});
});