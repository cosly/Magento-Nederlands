/*
 * 	Easy Slider 1.5 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/4004/easy-slider-15-the-easiest-jquery-plugin-for-sliding
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
/*
 *	markup example for $j("#slider").easySlider();
 *	
 * 	<div id="slider">
		<ul>				
			<li><a href="#"><img src="images/pro_1.png" alt="#" /></a></li>
			<li><a href="#"><img src="images/pro_3.png" alt="#" /></a></li>
			<li><a href="#"><img src="images/pro_2.png" alt="#" /></a></li>
			<li><a href="#"><img src="images/pro_4.png" alt="#" /></a></li>
			<li><a href="#"><img src="images/pro_1.png" alt="#" /></a></li>
			<li><a href="#"><img src="images/pro_3.png" alt="#" /></a></li>
			<li><a href="#"><img src="images/pro_2.png" alt="#" /></a></li>
			<li><a href="#"><img src="images/pro_4.png" alt="#" /></a></li>		
		</ul>
	</div>
 *
 */

 var $j = jQuery.noConflict(); 

$j(document).ready(function(){
    $j.fn.easySlider = function(options){
	  
		// default configuration properties
		var defaults = {			
			prevId: 		'prevBtn',
			prevText: 		'Previous',
			nextId: 		'nextBtn',	
			nextText: 		'Next',
			controlsShow:	true,
			controlsBefore:	'',
			controlsAfter:	'',	
			controlsFade:	true,
			firstId: 		'firstBtn',
			firstText: 		'First',
			firstShow:		false,
			lastId: 		'lastBtn',	
			lastText: 		'Last',
			lastShow:		false,				
			vertical:		false,
			speed: 			800,
			auto:			false,
			pause:			2000,
			continuous:		false
		}; 
		
		var options = $j.extend(defaults, options);  
				
		this.each(function() {  
			var obj = $j(this); 				
			var s = $j("li", obj).length;
			var w = $j("li", obj).width(); 
			var h = $j("li", obj).height(); 
			obj.width(w); 
			obj.height(h); 
			obj.css("overflow","hidden");
			var ts = s-1;
			var t = 0;
			$j("ul", obj).css('width',s*w);			
			if(!options.vertical) $j("li", obj).css('float','left');
			
			if(options.controlsShow){
				var html = options.controlsBefore;
				if(options.firstShow) html += '<span id="'+ options.firstId +'"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
				html += ' <span id="'+ options.prevId +'"><a href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
				html += ' <span id="'+ options.nextId +'"><a href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
				if(options.lastShow) html += ' <span id="'+ options.lastId +'"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';
				html += options.controlsAfter;						
				$j(obj).after(html);										
			};
	
			$j("a","#"+options.nextId).click(function(){		
				animate("next",true);
			});
			$j("a","#"+options.prevId).click(function(){		
				animate("prev",true);				
			});	
			$j("a","#"+options.firstId).click(function(){		
				animate("first",true);
			});				
			$j("a","#"+options.lastId).click(function(){		
				animate("last",true);				
			});		
			
			function animate(dir,clicked){
				var ot = t;				
				switch(dir){
					case "next":
						t = (ot>=ts) ? (options.continuous ? 0 : ts) : t+1;						
						break; 
					case "prev":
						t = (t<=0) ? (options.continuous ? ts : 0) : t-1;
						break; 
					case "first":
						t = 0;
						break; 
					case "last":
						t = ts;
						break; 
					default:
						break; 
				};	
				
				var diff = Math.abs(ot-t);
				var speed = diff*options.speed;						
				if(!options.vertical) {
					p = (t*w*-1);
					$j("ul",obj).animate(
						{ marginLeft: p }, 
						speed
					);				
				} else {
					p = (t*h*-1);
					$j("ul",obj).animate(
						{ marginTop: p }, 
						speed
					);					
				};
				
				if(!options.continuous && options.controlsFade){					
					if(t==ts){
						$j("a","#"+options.nextId).hide();
						$j("a","#"+options.lastId).hide();
					} else {
						$j("a","#"+options.nextId).show();
						$j("a","#"+options.lastId).show();					
					};
					if(t==0){
						$j("a","#"+options.prevId).hide();
						$j("a","#"+options.firstId).hide();
					} else {
						$j("a","#"+options.prevId).show();
						$j("a","#"+options.firstId).show();
					};					
				};				
				
				if(clicked) clearTimeout(timeout);
				if(options.auto && dir=="next" && !clicked){;
					timeout = setTimeout(function(){
						animate("next",false);
					},diff*options.speed+options.pause);
				};
				
			};
			// init
			var timeout;
			if(options.auto){;
				timeout = setTimeout(function(){
					animate("next",false);
				},options.pause);
			};		
		
			if(!options.continuous && options.controlsFade){					
				$j("a","#"+options.prevId).hide();
				$j("a","#"+options.firstId).hide();				
			};				
			
		});
	  
	};
});



