/* MouseEnterLeave.js v1.0.0 by Ken Snyder: http://kendsnyder.com/sandbox/enterleave/ */
(function() {
  var events = Prototype.Browser.IE ? 
    {"bindAsMouseEnter":"fromElement", "bindAsMouseLeave":"toElement"} :
    {"bindAsMouseEnter":"relatedTarget", "bindAsMouseLeave":"relatedTarget"};
  for (var eventName in events) {
    (function(relTargetProperty) {
      Function.prototype[eventName] = function() {
        var __method = this, args = $A(arguments), object = args.shift();
        return function(event) {
          event = event || window.event;
          try { 
            var relatedTarget = $(event[relTargetProperty]);
            if (relatedTarget && relatedTarget != this && !relatedTarget.descendantOf(this)) {
              __method.apply(object, [Event.extend(event)].concat(args));
            }
          } catch(e) {}
        };
      };
    })(events[eventName]);
  }
})();




var SilverFrameworkTabs = Class.create({
	initialize: function(className)	{
		this.className = className;
		
		var containersearch = $$('.' + this.className);
		if(containersearch.length > 0)	{
			this.container = containersearch.first();
		}
		
		this.bindEventHandlers();
	},
	
	hideTabs: function() {
		var search = this.container.down('.silverframework-product-tabs-content').select('.silverframework-product-tab-content');
		search.invoke('hide');
	},
	
	removeActive: function() {
		this.container.select('li.silverframework-tab-active').invoke('removeClassName', 'silverframework-tab-active');
	},
	
	getTabClickFunction: function(manager) {
		return function(e)	{
			
			e.preventDefault();
			
			var tab_id = false;
			var temp = $(e.element().parentNode).id.split('silverframework-product-tab-');

			if(temp.length == 2)	{
				tab_id = temp[1];
			}
			
			if(tab_id)	{
				var search = '.silverframework-product-tab-content-' + tab_id;
				var contents = manager.container.down('.silverframework-product-tabs-content').select(search);
				
				manager.hideTabs();
				
				if(contents.length > 0)	{
					var item = contents.first();
					item.show();
					
					/* Remove other tabs as active */
					manager.removeActive();					
					
					/* Set tab as active */
					$(e.element().parentNode).addClassName('silverframework-tab-active');
				}
			}
		}
	},
	
	bindEventHandlers: function() {
		var links = this.container.down('.silverframework-product-tabs').childElements('a');
		links.invoke('observe', 'click', this.getTabClickFunction(this));
	}
});




var SilverChooser = Class.create({
	initialize: function(className)	{
		this.className = className;
		this.status = 'hidden';
		this.event = 'mouseover';
		this.delay = 350;
	
		this.mouseoverTimeout = false;
		this.mouseoutTimeout = false;
	
		this.bindEventHandlers();
	},
	
	bindEventHandlers: function() {
		var path = '.' + this.className + ' a';
	
		if(this.event == 'click')	{
			$$(path).invoke('observe', this.event, this.getTriggerClickFunction(this));
		}
		else	{
			/* Add event handlers for the link */
			$$(path).invoke('observe', 'mouseover', this.getTriggerMouseoverFunction(this));
			$$(path).invoke('observe', 'mouseout', this.getTriggerMouseoutFunction(this));
			
			/* Add event handlers for the contents */
			var contents = this.getChooserContentsElement();
			
			var mouseoutcallback = this.getContentsMouseoutFunction(this);
			
			contents.observe('mouseover', this.getContentsMouseoverFunction(this));
			contents.observe('mouseout', mouseoutcallback.bindAsMouseLeave(null));
			
			//$('#silver-test').observe('mouseover', this.getContentsMouseoverFunction(this));
		}
	},
	
	hideContentsElement: function() {
		new Effect.SlideUp(this.getChooserContentsElement(), {
			duration: 0.2
		});		
		
		this.status = 'hidden';
	},
	
	showContentsElement: function() {
	
		this.positionContentsElement();
	
		new Effect.SlideDown(this.getChooserContentsElement(), {
			duration: 0.2
		});
		//this.getChooserContentsElement().show();
		
		this.status = 'displayed';
	},
	
	positionContentsElement: function() {
		var trigger = this.getTriggerElement();
		var chooser = this.getChooserContentsElement();
		var triggerPos = trigger.positionedOffset();
		
		var left = triggerPos[0];
		var top = (triggerPos[1] + trigger.getHeight());
		
		var viewport = document.viewport.getDimensions();
		
		if(viewport.width < (left + chooser.getWidth()))	{
			// If it is becomes wider than viewport
			left = left + trigger.getWidth() - chooser.getWidth();
		}
		
		chooser.setStyle({
			position: 'absolute',
			left: left + 'px',
			top: top + 'px'
		});	
	},
	
	getTriggerElement: function() {
		var path = '.' + this.className;
		var search = $$(path);
	
		if(search.length == 0)	{
			return false;
		}
		
		return search.first();
	},
	
	getChooserContentsElement: function()	{
		var path = '.' + this.className + ' .silverframework-chooser';
		var search = $$(path);
	
		if(search.length == 0)	{
			return false;
		}
		
		return search.first();
	},
	
	getContentsMouseoutFunction: function(manager) {
		return function(event)	{

			/* If it is displaying, and there is a mouseout timeout */
			if(manager.status == 'hidden')	{
				/* Cancel any timeout for displaying contents */
				if(manager.mouseoverTimeout != false)	{
					clearTimeout(manager.mouseoverTimeout);
				}
			}
			else	{
				/* Hide as normal */				
				manager.mouseoutTimeout = setTimeout(function() {
					manager.hideContentsElement();
				}, manager.delay);
			}	
		}
	},
	
	getContentsMouseoverFunction: function(manager) {
		return function(e)	{
		
			/* If it is displaying, and there is a mouseout timeout */
			if(manager.status != 'hidden' && manager.mouseoutTimeout != false)	{
				// Cancelling mouseout delay because mouseover contents instead
				clearTimeout(manager.mouseoutTimeout);
			}
		}
	},	
	
	getTriggerMouseoverFunction: function(manager) {
		return function(e)	{
			if(manager.status == 'hidden')	{
				manager.mouseoverTimeout = setTimeout(function() {
					manager.showContentsElement();
				}, manager.delay)
			} else	{
				if(manager.mouseoutTimeout)	{
					/* Cancelling mouseout timeout */
					clearTimeout(manager.mouseoutTimeout)
				}
			}
		}
	},
	
	getTriggerMouseoutFunction: function(manager) {
		return function(e)	{
			if(manager.status == 'hidden')	{
				/* Cancel any timeout for displaying contents */
				if(manager.mouseoverTimeout != false)	{
					clearTimeout(manager.mouseoverTimeout);
				}
			}
			else	{
				/* Hide as normal */				
				manager.mouseoutTimeout = setTimeout(function() {
					manager.hideContentsElement();
				}, manager.delay)
			}
		}
	},
	
	getTriggerClickFunction: function(manager) {
		return function(e)	{
			if(manager.status == 'hidden')	{
				manager.showContentsElement();
			}
			else	{
				manager.hideContentsElement();
			}
		}
	}
	
});



document.observe('dom:loaded', function() {
	/* Add event handler for currency chooser */
	var currency_search = $$('.silverframework-currency-chooser');
	if(currency_search.length > 0)	{
		new SilverChooser('silverframework-currency-chooser');
	}

	var storeview_search = $$('.silverframework-storeview-chooser');
	if(storeview_search.length > 0)	{
		new SilverChooser('silverframework-storeview-chooser');	
	}
	
	var auto_search = $$('.silverframework-auto-dropdown');
	for(var x=0; x < auto_search.length; x++)	{
		var classNames = $w(auto_search[x].className);
		for(var y=0; y < classNames.length; y++)	{
			var temp = classNames[y].split('silverframework-dropdown-');
			if(temp.length == 2)	{
				new SilverChooser(classNames[y]);	
			}
		}
	}
});