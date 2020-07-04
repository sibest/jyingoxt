jyingo.extend('table', function(self, settings, shared) {
	
	var _search = null;
	
	self.proto({
		
		
		initialize : function(params)
		{
			_search = this.get('search');
			if (_search)
			{
				
				_search.set_press_handler(this.create_delegate(this.search));
				
			}
		},
		
		search : function()
		{
			
			this.call(function(){}, 'search', _search.get_text());
			
		}
		
	});
	
	
	return new self();
	
});