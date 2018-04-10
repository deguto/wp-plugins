jQuery(document).ready(function(){
	jQuery('.default-value').each(function() {
	    var default_value = this.value;
	    jQuery(this).css('color', '#666'); // this could be in the style sheet instead
	    jQuery(this).focus(function() {
	        if(this.value == default_value) {
	            this.value = '';
	            jQuery(this).css('color', '#333');
	        }
	    });
	    jQuery(this).blur(function() {
	        if(this.value == '') {
	            jQuery(this).css('color', '#666');
	            this.value = default_value;
	        }
	    });
	});
});

