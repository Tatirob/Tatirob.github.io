/* ========================================================================
 * Bootstrap: tab.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#tabs
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+function ($) { "use strict";

  // TAB CLASS DEFINITION
  // ====================

  var Tab = function (element) {
    this.element = $(element)
  }

  Tab.prototype.show = function () {
    var $this    = this.element
    var $ul      = $this.closest('ul:not(.dropdown-menu)')
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
    }

    if ($this.parent('li').hasClass('active')) return

    var previous = $ul.find('.active:last a')[0]
    var e        = $.Event('show.bs.tab', {
      relatedTarget: previous
    })

    $this.trigger(e)

    if (e.isDefaultPrevented()) return

    var $target = $(selector)

    this.activate($this.parent('li'), $ul)
    this.activate($target, $target.parent(), function () {
      $this.trigger({
        type: 'shown.bs.tab'
      , relatedTarget: previous
      })
    })
  }

  Tab.prototype.activate = function (element, container, callback) {
    var $active    = container.find('> .active')
    var transition = callback
      && $.support.transition
      && $active.hasClass('fade')

    function next() {
      $active
        .removeClass('active')
        .find('> .dropdown-menu > .active')
        .removeClass('active')

      element.addClass('active')

      if (transition) {
        element[0].offsetWidth // reflow for transition
        element.addClass('in')
      } else {
        element.removeClass('fade')
      }

      if (element.parent('.dropdown-menu')) {
        element.closest('li.dropdown').addClass('active')
      }

      callback && callback()
    }

    transition ?
      $active
        .one($.support.transition.end, next)
        .emulateTransitionEnd(150) :
      next()

    $active.removeClass('in')
  }


  // TAB PLUGIN DEFINITION
  // =====================

  var old = $.fn.tab

  $.fn.tab = function ( option ) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.tab')

      if (!data) $this.data('bs.tab', (data = new Tab(this)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.tab.Constructor = Tab


  // TAB NO CONFLICT
  // ===============

  $.fn.tab.noConflict = function () {
    $.fn.tab = old
    return this
  }


  // TAB DATA-API
  // ============

  $(document).on('click.bs.tab.data-api', '[data-toggle="tab"], [data-toggle="pill"]', function (e) {
    e.preventDefault()      
    $(this).tab('show')
    $.force_appear()  
  })

}(window.jQuery);

/* ========================================================================
 * Bootstrap: collapse.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#collapse
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+function ($) { "use strict";

  // COLLAPSE PUBLIC CLASS DEFINITION
  // ================================

  var Collapse = function (element, options) {
    this.$element      = $(element)
    this.options       = $.extend({}, Collapse.DEFAULTS, options)
    this.transitioning = null

    if (this.options.parent) this.$parent = $(this.options.parent)
    if (this.options.toggle) this.toggle()
  }

  Collapse.DEFAULTS = {
    toggle: true
  }

  Collapse.prototype.dimension = function () {
    var hasWidth = this.$element.hasClass('width')
    return hasWidth ? 'width' : 'height'
  }

  Collapse.prototype.show = function () {
    if (this.transitioning || this.$element.hasClass('in')) return

    var startEvent = $.Event('show.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var actives = this.$parent && this.$parent.find('> .panel > .in')

    if (actives && actives.length) {
      var hasData = actives.data('bs.collapse')
      if (hasData && hasData.transitioning) return
      actives.collapse('hide')
      hasData || actives.data('bs.collapse', null)
    }

    var dimension = this.dimension()

    this.$element
      .removeClass('collapse')
      .addClass('collapsing')
      [dimension](0)

    this.transitioning = 1

    var complete = function () {
      this.$element
        .removeClass('collapsing')
        .addClass('in')
        [dimension]('auto')
      this.transitioning = 0
      this.$element.trigger('shown.bs.collapse')
    }

    if (!$.support.transition) return complete.call(this)

    var scrollSize = $.camelCase(['scroll', dimension].join('-'))

    this.$element
      .one($.support.transition.end, $.proxy(complete, this))
      .emulateTransitionEnd(350)
      [dimension](this.$element[0][scrollSize])
  }

  Collapse.prototype.hide = function () {
    if (this.transitioning || !this.$element.hasClass('in')) return

    var startEvent = $.Event('hide.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var dimension = this.dimension()

    this.$element
      [dimension](this.$element[dimension]())
      [0].offsetHeight

    this.$element
      .addClass('collapsing')
      .removeClass('collapse')
      .removeClass('in')

    this.transitioning = 1

    var complete = function () {
      this.transitioning = 0
      this.$element
        .trigger('hidden.bs.collapse')
        .removeClass('collapsing')
        .addClass('collapse')
    }

    if (!$.support.transition) return complete.call(this)

    this.$element
      [dimension](0)
      .one($.support.transition.end, $.proxy(complete, this))
      .emulateTransitionEnd(350)
  }

  Collapse.prototype.toggle = function () {
    this[this.$element.hasClass('in') ? 'hide' : 'show']()
  }


  // COLLAPSE PLUGIN DEFINITION
  // ==========================

  var old = $.fn.collapse

  $.fn.collapse = function (option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.collapse')
      var options = $.extend({}, Collapse.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data) $this.data('bs.collapse', (data = new Collapse(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.collapse.Constructor = Collapse


  // COLLAPSE NO CONFLICT
  // ====================

  $.fn.collapse.noConflict = function () {
    $.fn.collapse = old
    return this
  }


  // COLLAPSE DATA-API
  // =================

  $(document).on('click.bs.collapse.data-api', '[data-toggle=collapse]', function (e) {
    var $this   = $(this), href
    var target  = $this.attr('data-target')
        || e.preventDefault()
        || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') //strip for ie7
    var $target = $(target)
    var data    = $target.data('bs.collapse')
    var option  = data ? 'toggle' : $this.data()
    var parent  = $this.attr('data-parent')
    var $parent = parent && $(parent)

    if (!data || !data.transitioning) {
      if ($parent) $parent.find('[data-toggle=collapse][data-parent="' + parent + '"]').not($this).addClass('collapsed')
      $this[$target.hasClass('in') ? 'addClass' : 'removeClass']('collapsed')
    }
	
	$this.toggleClass("active");
	
    $target.collapse(option)
  })

}(window.jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#transitions
 * ========================================================================
 * Copyright 2013 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+function ($) { "use strict";

  // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
  // ============================================================

  function transitionEnd() {
    var el = document.createElement('bootstrap')

    var transEndEventNames = {
      'WebkitTransition' : 'webkitTransitionEnd'
    , 'MozTransition'    : 'transitionend'
    , 'OTransition'      : 'oTransitionEnd otransitionend'
    , 'transition'       : 'transitionend'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }
  }

  // http://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
    var called = false, $el = this
    $(this).one($.support.transition.end, function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()
  })

}(window.jQuery);


(function (root, factory) {
	
	"use strict";
	
	if (typeof define === 'function' && define.amd) {
			
		define(factory);

	} else if (typeof exports === 'object') {

		module.exports = factory(require, exports, module);

	} else {

		root.CountUp = factory();

	}
	
	} (this, function (require, exports, module) {

	"use strict";
	
	/*
		countUp.js
		by @inorganik
	*/

	// target = id of html element or var of previously selected html element where counting occurs
	// startVal = the value you want to begin at
	// endVal = the value you want to arrive at
	// decimals = number of decimal places, default 0
	// duration = duration of animation in seconds, default 2
	// options = optional object of options (see below)

	var CountUp = function (target, startVal, endVal, decimals, duration, options) {

		var self = this;
		self.version = function () {
			return '1.9.3';
		};

		// default options
		self.options = {
			useEasing: true, // toggle easing
			useGrouping: true, // 1,000,000 vs 1000000
			separator: ',', // character to use as a separator
			decimal: '.', // character to use as a decimal
			easingFn: easeOutExpo, // optional custom easing function, default is Robert Penner's easeOutExpo
			formattingFn: formatNumber, // optional custom formatting function, default is formatNumber above
			prefix: '', // optional text before the result
			suffix: '', // optional text after the result
			numerals: [] // optionally pass an array of custom numerals for 0-9
		};

		// extend default options with passed options object
		if (options && typeof options === 'object') {
			for (var key in self.options) {
				if (options.hasOwnProperty(key) && options[key] !== null) {
					self.options[key] = options[key];
				}
			}
		}

		if (self.options.separator === '') {
			self.options.useGrouping = false;
		} else {
			// ensure the separator is a string (formatNumber assumes this)
			self.options.separator = '' + self.options.separator;
		}

		// make sure requestAnimationFrame and cancelAnimationFrame are defined
		// polyfill for browsers without native support
		// by Opera engineer Erik Möller
		var lastTime = 0;
		var vendors = ['webkit', 'moz', 'ms', 'o'];
		for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
			window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
			window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
		}
		if (!window.requestAnimationFrame) {
			window.requestAnimationFrame = function (callback) {
				var currTime = new Date().getTime();
				var timeToCall = Math.max(0, 16 - (currTime - lastTime));
				var id = window.setTimeout(function () {
					callback(currTime + timeToCall);
				}, timeToCall);
				lastTime = currTime + timeToCall;
				return id;
			};
		}
		if (!window.cancelAnimationFrame) {
			window.cancelAnimationFrame = function (id) {
				clearTimeout(id);
			};
		}

		function formatNumber(num) {
			var neg = (num < 0),
				x, x1, x2, x3, i, len;
			num = Math.abs(num).toFixed(self.decimals);
			num += '';
			x = num.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? self.options.decimal + x[1] : '';
			if (self.options.useGrouping) {
				x3 = '';
				for (i = 0, len = x1.length; i < len; ++i) {
					if (i !== 0 && ((i % 3) === 0)) {
						x3 = self.options.separator + x3;
					}
					x3 = x1[len - i - 1] + x3;
				}
				x1 = x3;
			}
			// optional numeral substitution
			if (self.options.numerals.length) {
				x1 = x1.replace(/[0-9]/g, function (w) {
					return self.options.numerals[+w];
				});
				x2 = x2.replace(/[0-9]/g, function (w) {
					return self.options.numerals[+w];
				});
			}
			return (neg ? '-' : '') + self.options.prefix + x1 + x2 + self.options.suffix;
		}
		// Robert Penner's easeOutExpo
		function easeOutExpo(t, b, c, d) {
			return c * (-Math.pow(2, -10 * t / d) + 1) * 1024 / 1023 + b;
		}

		function ensureNumber(n) {
			return (typeof n === 'number' && !isNaN(n));
		}

		self.initialize = function () {
			
			if(self.initialized) { return true; }

			self.error = '';
			self.d = (typeof target === 'string') ? document.getElementById(target) : target;
			if (!self.d) {
				self.error = '[CountUp] target is null or undefined';
				return false;
			}
			self.startVal = Number(startVal);
			self.endVal = Number(endVal);
			// error checks
			if (ensureNumber(self.startVal) && ensureNumber(self.endVal)) {
				self.decimals = Math.max(0, decimals || 0);
				self.dec = Math.pow(10, self.decimals);
				self.duration = Number(duration) * 1000 || 2000;
				self.countDown = (self.startVal > self.endVal);
				self.frameVal = self.startVal;
				self.initialized = true;
				return true;
			} else {
				self.error = '[CountUp] startVal (' + startVal + ') or endVal (' + endVal + ') is not a number';
				return false;
			}
		};

		// Print value to target
		self.printValue = function (value) {
			var result = self.options.formattingFn(value);

			if (self.d.tagName === 'INPUT') {
				this.d.value = result;
			} else if (self.d.tagName === 'text' || self.d.tagName === 'tspan') {
				this.d.textContent = result;
			} else {
				this.d.innerHTML = result;
			}
		};

		self.count = function (timestamp) {

			if (!self.startTime) {
				self.startTime = timestamp;
			}

			self.timestamp = timestamp;
			var progress = timestamp - self.startTime;
			self.remaining = self.duration - progress;

			// to ease or not to ease
			if (self.options.useEasing) {
				if (self.countDown) {
					self.frameVal = self.startVal - self.options.easingFn(progress, 0, self.startVal - self.endVal, self.duration);
				} else {
					self.frameVal = self.options.easingFn(progress, self.startVal, self.endVal - self.startVal, self.duration);
				}
			} else {
				if (self.countDown) {
					self.frameVal = self.startVal - ((self.startVal - self.endVal) * (progress / self.duration));
				} else {
					self.frameVal = self.startVal + (self.endVal - self.startVal) * (progress / self.duration);
				}
			}

			// don't go past endVal since progress can exceed duration in the last frame
			if (self.countDown) {
				self.frameVal = (self.frameVal < self.endVal) ? self.endVal : self.frameVal;
			} else {
				self.frameVal = (self.frameVal > self.endVal) ? self.endVal : self.frameVal;
			}

			// decimal
			self.frameVal = Math.round(self.frameVal * self.dec) / self.dec;

			// format and print value
			self.printValue(self.frameVal);

			// whether to continue
			if (progress < self.duration) {
				self.rAF = requestAnimationFrame(self.count);
			} else {
				
				if (self.callback) { 
					self.callback();
				}
				
			}
		};
		// start your animation
		self.start = function (callback) {
			
			if (!self.initialize()) { 
				return;
			}
			
			self.callback = callback;
			self.rAF = requestAnimationFrame(self.count);
		};
		// toggles pause/resume animation
		self.pauseResume = function () {
			if (!self.paused) {
				self.paused = true;
				cancelAnimationFrame(self.rAF);
			} else {
				self.paused = false;
				delete self.startTime;
				self.duration = self.remaining;
				self.startVal = self.frameVal;
				requestAnimationFrame(self.count);
			}
		};
		// reset to startVal so animation can be run again
		self.reset = function () {
			self.paused = false;
			delete self.startTime;
			self.initialized = false;
			if (self.initialize()) {
				cancelAnimationFrame(self.rAF);
				self.printValue(self.startVal);
			}
		};
		// pass a new endVal and start animation
		self.update = function (newEndVal) {
			
			if (!self.initialize()) { 
				return;
			}
			
			newEndVal = Number(newEndVal);
			if (!ensureNumber(newEndVal)) {
				self.error = '[CountUp] update() - new endVal is not a number: ' + newEndVal;
				return;
			}
			
			self.error = '';
			if (newEndVal === self.frameVal) { 
				return; 
			}
			
			cancelAnimationFrame(self.rAF);
			self.paused = false;
			delete self.startTime;
			self.startVal = self.frameVal;
			self.endVal = newEndVal;
			self.countDown = (self.startVal > self.endVal);
			self.rAF = requestAnimationFrame(self.count);
		};

		// format startVal on initialization
		if(self.initialize()) { 
			
			self.printValue(self.startVal); 
		
		}
		
	};

	return CountUp;

}));

/* <![CDATA[ */
(function($){
    
    "use strict";
    
	var ut_image_observer = lozad('.ut-lazy', {
    	loaded: function(el) {

			var $element = $(el);

			$element.closest(".ut-image-gallery-item").addClass("ut-image-loaded");
			$element.addClass("ut-image-loaded");

			$.force_appear(); 

		}

	});

	ut_image_observer.observe();
    
    /* Restart Observer to check if new images have become available with the last ajax request
    ================================================== */
    $(document).ajaxComplete(function() {
      
        ut_image_observer.observe();
        
    });
	
    /* Check if Element is visible
    ================================================== */
    $.fn.visible = function(partial,hidden){
		
	    var $t				= $(this).eq(0),
	    	t				= $t.get(0),
	    	$w				= $(window),
	    	viewTop			= $w.scrollTop(),
	    	viewBottom		= viewTop + $w.height(),
	    	_top			= $t.offset().top,
	    	_bottom			= _top + $t.height(),
	    	compareTop		= partial === true ? _bottom : _top,
	    	compareBottom	= partial === true ? _top : _bottom,
	    	clientSize		= hidden === true ? t.offsetWidth * t.offsetHeight : true;
		
		return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
    };    
    
    /* Check if Element is in Viewport Plugin
    ================================================== */
    $.fn.isOnScreen = function(){

        var win = $(window);

        var viewport = {
            top : win.scrollTop(),
            left : win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();

        var bounds = this.offset();
        bounds.right = bounds.left + this.outerWidth();
        bounds.bottom = bounds.top + this.outerHeight();

        return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

    };
    
    $(document).ready(function(){
        
        /* Helper Functions
        ================================================== */
        function create_id() {
             return '-' + Math.random().toString(36).substr(2, 9);
        }
                
        /* FitVid
        ================================================== */
        $(".ut-video").fitVids();
        
        
        /* United Video Player
        ================================================== */
        function ut_load_video_player(url, uniqueID, $parent, callback){
                
            if( !url ) {
                return;
            }
                        
            var ajaxURL = utShortcode.ajaxurl,
                $video = $('<div id="ut-video'+uniqueID+'"></div>'),
                $caption = $parent.find('.ut-video-module-caption-text');            
            
            $.ajax({
              
              type: 'POST',
              url: ajaxURL,
              data: {"action": "ut_get_video_player", "video" : url },
              success: function(response) {                  
                  
                  $video.html(response).fitVids();                      
                  $parent.html( $video.append($caption) );
                  
                  return false;
                                   
              },
              complete : function() {
                                
                  if (callback && typeof(callback) === "function") {   
                      callback();  
                  }
                        
              }
                    
            });
        
        }
        
        $(document).on('click', '.ut-video-module-caption .ut-load-video', function(event) {        
                
            var url = $(this).data('video'),
                uniqueID = create_id(),
                $parent = $(this).parent('.ut-video-module-caption'),
                $loader = $parent.next('.ut-video-module-loading');
            
            $parent.find(".ut-video-module-caption-text").fadeOut();
            $loader.fadeIn();
                
            ut_load_video_player(url, uniqueID, $parent, function() {
                
                $loader.fadeOut();
                
            });
            
            event.preventDefault();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        });
        
        // deprecated shortcode fallback
        function ut_load_video_united_player(url, uniqueID, $parent, callback){
                
            if( !url ) {
                return;
            }
                        
            var ajaxURL = utShortcode.ajaxurl,
                $video = $('<div id="ut-video'+uniqueID+'"></div>'),
                $caption = $parent.find('.ut-video-caption-text');            
            
            $.ajax({
              
              type: 'POST',
              url: ajaxURL,
              data: {"action": "ut_get_video_player", "video" : url },
              success: function(response) {                  
                  
                  $video.html(response).fitVids();                      
                  $parent.html( $video.append($caption) );
                  
                  return false;
                                   
              },
              complete : function() {
                                
                  if (callback && typeof(callback) === "function") {   
                      callback();  
                  }
                        
              }
                    
            });
        
        }
        
        $(document).on('click', '.ut-video-caption .ut-load-video', function(event) {        
                
            var url = $(this).data('video'),
                uniqueID = create_id(),
                $parent = $(this).parent('.ut-video-caption'),
                $loader = $parent.next('.ut-video-loading');
            
            $parent.find(".ut-video-caption-text").fadeOut();
            $loader.fadeIn();
                
            ut_load_video_united_player(url, uniqueID, $parent, function() {
                
                $loader.fadeOut();
                
            });
            
            event.preventDefault();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        });
        
        
        /* Deactivated Link
		================================================== */
        $(document).on('click', '.ut-deactivated-link', function(event) {
        
            event.preventDefault();
            
        });
        
        /* Image Gallery Module
		================================================== */
        if ( $().lightGallery ) {
                        
            $('.entry-content').lightGallery({
                selector: '.ut-vc-images-lightbox:not(.ut-vc-images-lightbox-group-image)',
                exThumbImage: 'data-exthumbimage',
                download: site_settings.lg_download,
                hash: false
            });            
            
            $(document).ajaxComplete(function() {
                
                /* restart */    
                $('.vc_media_grid').lightGallery({
                    selector: '.ut-vc-ajax-images-lightbox',
                    exThumbImage: 'data-exthumbimage',
                    download: site_settings.lg_download,
                    hash: false
                });
                
            });            
            
        }
        
        /* Blog Article Animation
        ================================================== */    
        if( $("body").hasClass("ut-blog-has-animation") ) {
        
            $('article').appear();
            
            $('article').each(function(i){
                
                $(this).css('z-index', $('article').length+i);                
                
            });            
            
            $(document.body).on('appear', 'article', function() {            

                if( !$(this).hasClass('BrooklynFadeInUp') ) {

                    $(this).addClass('BrooklynFadeInUp');    

                }
                
            });        
        
        }
            
        /* Milestone Counter - animate when visible on load or on appear
        ================================================== */        
        $('.ut-counter').appear().addClass('ut-appear-initialized');
        
        $(document.body).on('appear', '.ut-counter', function() {
            
			var $this = $(this);
			
            if ( !$this.hasClass('ut-already-counted') ) {

                $this.addClass('ut-already-counted');

				var options = {
				  	useEasing: true,
				  	useGrouping: true,
				  	separator: $this.data('sep-sign'),
				  	decimal: '.',
					suffix: $this.data('suffix'), 
					prefix: $this.data('prefix'),
					
				};
				
				// initialize counter
				var count = new CountUp( $this.find('.ut-count').attr("id"), 0, $this.data('counter'), 0, $this.data('speed')/1000, options);
				
				// run it
				count.start();				

            }

        });    
        
        
        /* Element Effects
        ================================================== */
        $('.ut-animate-element').appear().addClass('ut-appear-initialized')
    
        $(document.body).on('webkitAnimationStart mozAnimationStart MSAnimationStart oanimationstart animationstart', '.ut-animate-element, .ut-animate-image', function() {

            var $this = $(this);
            
            if( !$this.hasClass( $this.data("effect") ) ) {
                return;
            }
            
            // extra class    
            $this.addClass('ut-element-is-animating');

        });        

        $(document.body).on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', '.ut-animate-element, .ut-animate-image', function() {

            var $this  = $(this);    
            var effect = $this.data('effect');
                        
            if( !$this.hasClass( effect ) ) {
                return;
            }
            
            // extra class
            $this.removeClass('ut-element-is-animating');

            // start animation again            
            if( $this.data('animation-between') ) {

                $this.removeClass(effect).delay( $this.data('animation-between') * 1000 ).queue( function() {

                    $this.addClass( effect ).dequeue();

                });

            }

            // check if element is hidden and will be animated again
            if( $this.data('animateonce') === 'no' && !$this.isOnScreen() ) {

                $this.clearQueue().removeClass( effect ).css('opacity','0').dequeue();                   

            }

        });             

        $(document.body).on('webkitAnimationStart mozAnimationStart MSAnimationStart oanimationstart animationstart', '.ut-animate-gallery-element', function() {
            
            var $this = $(this);
            
            if( !$this.hasClass( $this.data("effect") ) ) {
                return;
            }
            
            // extra class    
            $this.addClass('ut-element-is-animating');

        });        

        $(document.body).on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', '.ut-animate-gallery-element', function() {
            
            var $this  = $(this);    
            var effect = $this.data('effect');
            
            if( !$this.hasClass( effect ) ) {
                return;
            }
            
            // extra class
            $this.removeClass('ut-element-is-animating');

            // start animation again            
            if( $this.data('animation-between') ) {

                $this.removeClass(effect).delay( $this.data('animation-between') ).queue( function() {

                    $this.addClass( effect ).dequeue();

                });

            }

            // check if element is hidden and will be animated again
            if( $this.data('animateonce') === 'no' && !$this.isOnScreen() ) {

                $this.clearQueue().removeClass( effect ).css('opacity','0').dequeue();                   

            }

        });

        $(document.body).on('appear', '.ut-animate-element', function() {

            var $this = $(this),
                effect = $this.data('effect');

            if( $this.hasClass('ut-animation-complete') || $this.hasClass('ut-element-is-animating') ) {
                return;
            }

            if( $this.data('animation-duration') ) {
				
                $this.css('animation-duration', $this.data('animation-duration') );

            }
            
            $this.delay( $this.data('delay') ).queue( function() {

                $this.css('opacity','1').addClass( effect ).dequeue();

            });


        });

        $(document.body).on('disappear', '.ut-animate-element', function() {

            var $this  = $(this),
                effect = $this.data('effect');


            if( $this.hasClass('ut-animation-complete') || $this.hasClass('ut-element-is-animating') ) {
                return;
            }

            if( $this.data('animateonce') === 'no' ) {

                $this.clearQueue().removeClass( effect ).css('opacity','0').dequeue();                     

            } else {
                
                if( $this.hasClass( effect ) ) {
                
                    $this.addClass('ut-animation-complete');
                    
                }

            }

        });

        /* Animate Image
        ================================================== */
        $('.ut-animate-image').appear().addClass('ut-appear-initialized');;
        
        $(document.body).on('appear', '.ut-animate-image', function() {

            var $this = $(this),
                effect = $this.data('effect');

           if( $this.hasClass('ut-animation-complete') || $this.hasClass('ut-element-is-animating') ) {
                return;
            }

            if( $this.data('animation-duration') ) {

                $this.css('animation-duration', $this.data('animation-duration') );

            }

            if( $this.data('animation-between') ) {

                $this.css('animation-delay', $this.data('animation-between') );

            }

            $this.delay( $this.data('delay') ).queue( function() {

                $this.css('opacity','1').addClass( effect ).dequeue();

            });

        });

        $(document.body).on('disappear', '.ut-animate-image', function() {

            var $this  = $(this),
                effect = $this.data('effect');

            if( $this.hasClass('ut-animation-complete') || $this.hasClass('ut-element-is-animating') ) {
                return;
            }

            if( $this.data('animateonce') === 'no' ) {

                $this.clearQueue().removeClass( effect ).css('opacity','0').dequeue();                   

            } else {
                
                if( $this.hasClass( effect ) ) {
                
                    $this.addClass('ut-animation-complete');
                    
                }

            }

        });
                
        /* Skillbar
        ================================================== */
        $('.ut-skill-active').appear().addClass('ut-appear-initialized');
                
        $(document.body).on('appear', '.ut-skill-active', function() {
            
            var $this     = $(this),
                bar_width = $this.data('width');
                    
                if( !$this.hasClass('ut-already-visible') ) {
                    
                    if(  $this.hasClass('ut-skill-progress-thin') ) {
                        
                        $this.addClass("ut-already-visible").width(bar_width + "%");
                        
                    } else {                    
                    
                        $this.addClass("ut-already-visible").stop(true, true).animate({width : bar_width+"%"} , $this.data("speed") );
                    
                    }
                
                }            
            
                        
        });
        
        $(document.body).on('disappear', '.ut-skill-active', function() {
            
            if( $(this).data('animateonce') === 'no' ) {
                                    
                $(this).removeClass("ut-already-visible").css('width',0);
                
            }
                        
        });
        
        /* Progress Circles 
        ================================================== */
        $('.bkly-progress-svg').appear().addClass('ut-appear-initialized');

        $(document.body).on('appear', '.bkly-progress-svg', function() {

            var $this = $(this);

            if( $this.hasClass('ut-animation-complete') ) {
                return;
            }

            var totalProgress = $this.find('.circle').attr('stroke-dasharray'),
                progress      = $this.parent().data('circle-percent');

            $this.find('.stroke').get(0).style['stroke-dashoffset'] = -502.4 + ( totalProgress * progress / 100 );
            $this.find('.circle').get(0).style['stroke-dashoffset'] = totalProgress * progress / 100;        

        });

        $(document.body).on('disappear', '.bkly-progress-svg', function() {

            var $this  = $(this);

            if( $this.data('animateonce') === 'no' ) {

                $this.find('.stroke').get(0).style['stroke-dashoffset'] = -502.4;
                $this.find('.circle').get(0).style['stroke-dashoffset'] = 0;

            } else {

                $this.addClass('ut-animation-complete');

            }

        });
        
        $(document).ajaxComplete(function() {
            
            $('.ut-counter:not(.ut-appear-initialized)').appear().addClass('ut-appear-initialized');
            $('.ut-skill-active:not(.ut-appear-initialized)').appear().addClass('ut-appear-initialized');
            $('.ut-animate-image:not(.ut-appear-initialized)').appear().addClass('ut-appear-initialized');
            $('.ut-animate-element:not(.ut-appear-initialized)').appear().addClass('ut-appear-initialized');
            $('.bkly-progress-svg:not(.ut-appear-initialized)').appear().addClass('ut-appear-initialized');
            
        });
        
    });
    
    
    /* Gallery Slider
    ================================================== */
    var user_can_click = true;
    
    function delay_click( timer ) {
        
        setTimeout(function() {
            
            user_can_click = true;
        
        }, timer );
    
    }

    $(document).on('click', '.ut-next-gallery-slide:not(.ut-single-slider-control)', function(event) {
        
        event.stopImmediatePropagation();
        event.preventDefault();
        
        if( !user_can_click ) {
            return;
        }
        
        var $owl = $('#' + $(this).data('for') );
        $owl.trigger('prev.owl.carousel'); 
        
        user_can_click = false;        
        delay_click( 200 ); // should be same as animation speed in css        
        
    });
   
    $(document).on('click', '.ut-prev-gallery-slide:not(.ut-single-slider-control)', function(event) {
        
        event.stopImmediatePropagation();
        event.preventDefault();
        
        if( !user_can_click ) {
            return;
        }
        
        var $owl = $('#' + $(this).data('for') );
        $owl.trigger('next.owl.carousel');
        
        user_can_click = false;        
        delay_click( 200 ); // should be same as animation speed in css                 
        
    });    
    
	
	$(document).on('click', '.ut-next-gallery-slide.ut-single-slider-control', function(event) {
        
        event.stopImmediatePropagation();
        event.preventDefault();
        
        if( !user_can_click ) {
            return;
        }
        
        var $owl = $('#' + $(this).data('for') );
        $owl.trigger('next.owl.carousel');
        
        user_can_click = false;        
        delay_click( 200 ); // should be same as animation speed in css        
        
    });
   
    $(document).on('click', '.ut-prev-gallery-slide.ut-single-slider-control', function(event) {
        
        event.stopImmediatePropagation();
        event.preventDefault();
        
        if( !user_can_click ) {
            return;
        }
        
        var $owl = $('#' + $(this).data('for') );
        $owl.trigger('prev.owl.carousel');
        
        user_can_click = false;        
        delay_click( 200 ); // should be same as animation speed in css                 
        
    }); 
	
    /* Load Instagram Feeds
    ================================================== */
    var instagram_is_loading = false,
        load_instagram_images_on_scroll = false;
    
    $(window).load(function(){
        
        $(".ut-instagram-gallery-wrap").each( function(){
        
            $(this).height( $(this).height() );

        });
        
    });
    
    function ut_load_instagram_feeds( $gallery, $clear, atts, callback ){

        if( !atts ) {
            return;
        }

        $.ajax({

          type: 'POST',
          url: utShortcode.ajaxurl,
          data: {
              "action": "ut_get_gallery_instagram_feed", 
              "atts" : JSON.stringify(atts),
          },
          success: function(response) {                  
            
              // update atts on gallery
              $gallery.data("atts", response.atts);
              
              // get new items
              var $newItems = $(response.feeds );
              
              // hide items since images are not loaded yet
              $newItems.find(".ut-image-gallery-item").hide();              
              $newItems.insertBefore( $clear );
              
              // wait for images
              $newItems.imagesLoaded( function() {
                  
                  // show new images
                  $newItems.find(".ut-image-gallery-item").show();
                  
                  // animate container height
                  $gallery.parent(".ut-instagram-gallery-wrap").height( $gallery.height() );
                  
                  // run appear for possible animations
                  $.force_appear();
                  
                  // remove flag
                  instagram_is_loading = false;
                  
              });
              
              /* restart */    
              $('.ut-instagram-gallery').lightGallery({
                    selector: '.ut-vc-images-lightbox',
                    exThumbImage: 'data-exthumbimage',
                    download: site_settings.lg_download,
                    hash: false
              });
              
              return false;

          },
          complete : function() {

              if (callback && typeof(callback) === "function") {   
                  callback();  
              }

          }

        });

    }
    
    $(document).on('click', '.ut-load-instagram-feeds', function(event) {        
        
        var instagram_gallery_id = $(this).data('for'),
            $button = $(this);
        
        if( instagram_is_loading ) {
            return false;
        }
        
        // set flag
        instagram_is_loading = true;
        
        // hide load more button - will be replaced with a loading icon on scroll
        $button.fadeOut();
        
        // load feeds
        ut_load_instagram_feeds( $(instagram_gallery_id), $(instagram_gallery_id + '_clear') , $(instagram_gallery_id).data("atts"), function() {
            
            // remove flag
            instagram_is_loading = false;
            
            // activate scroll loading
            load_instagram_images_on_scroll = true;

        });

        event.preventDefault();
        
    });    
    
    
    $(window).scroll( function(){
        
        if( !load_instagram_images_on_scroll || instagram_is_loading ) {
            return;
        }
        
        $('.ut-instagram-gallery').each(function(){
                       
            var $this = $(this);
            
            if( $(window).scrollTop() >= $this.offset().top + $this.outerHeight() - window.innerHeight) {
                
                $this.find(".ut-instagram-module-loading").fadeIn();
                
                // set flag
                instagram_is_loading = true;

                // load feeds
                ut_load_instagram_feeds( $this, $('#' + $this.attr("id") + '_clear') , $this.data("atts"), function() {
                    
                    $this.find(".ut-instagram-module-loading").fadeOut();

                });

            }
            
        });
        
        
    });
    
    // force visible elements to appear after load
    $(window).load( function(){
        
        $.force_appear();
                   
    });        

})(jQuery);
 /* ]]> */