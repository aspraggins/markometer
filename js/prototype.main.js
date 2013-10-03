Event.observe(window, 'load', function() {
	// alert-holder
	$$('div.alert-holder').each(function(_aHolder, i){
		var _remove = _aHolder.getElementsBySelector('a.close')[0];
		_remove.observe('click', function(e){
			_aHolder.remove();
			e.preventDefault();
		});
	});
	
	// open/close
	var _slideEffect = 0, _animate = false;
	
	slideThis('div.section-container', 'div.head a', 'div.section-holder');
	slideThis('div.signin-form', 'a.forgot', 'div.error-block');
	slideThis('form.contact-form', 'a.opener', 'div.slider');
	
	function slideThis(container,link,slide) {
		$$(container).each(function(_holder, i){
			var _link = _holder.getElementsBySelector(link)[0],
				_cancel = _holder.getElementsBySelector('a.cancel')[0],
				_slide = _holder.getElementsBySelector(slide)[0];
				
			_slide.writeAttribute('id','slide'+i)
			if (!_link.hasClassName('active')) {
				_slide.hide();
				_holder.removeClassName('active-slide');
			} else {
				_holder.addClassName('active-slide');
			}
			
			_link.observe('click', function(e){
				e.preventDefault();
				if (_animate) return false;
				_animate = true;
				if (!_holder.hasClassName('active-slide')) {
					_holder.addClassName('active-slide');
					Effect.SlideDown('slide'+i, {duration: 1.0, afterFinish:function(){_animate=false} });
				} else {
					_holder.removeClassName('active-slide');
					Effect.SlideUp('slide'+i, {duration: 1.0, afterFinish:function(){_animate=false} });
				}
			});
			if (_cancel) {
				_cancel.observe('click', function(e){
					e.preventDefault();
					if (_animate) return false;
					_animate = true;
					_holder.removeClassName('active-slide');
					Effect.SlideUp('slide'+i, {duration: 1.0, afterFinish:function(){_animate=false} });
				});
			}
		});
	}
});