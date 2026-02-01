/**
 * Lost & Found Animal - Frontend JS
 * 
 * @author Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.1
 */

(function($) {
    'use strict';
    
    // ===================
    // FILTERS
    // ===================
    var LFA_Filters = {
        
        init: function() {
            var self = this;
            
            this.$grid = $('#lfa-grid');
            this.$cards = this.$grid.find('.lfa-card');
            this.$status = $('#lfa-filter-status');
            this.$gender = $('#lfa-filter-gender');
            this.$sort = $('#lfa-sort');
            this.$reset = $('#lfa-reset');
            this.$count = $('#lfa-count');
            this.$noResults = $('#lfa-no-results');
            
            if (!this.$grid.length) return;
            
            this.$status.on('change', function() { self.apply(); });
            this.$gender.on('change', function() { self.apply(); });
            this.$sort.on('change', function() { self.apply(); });
            this.$reset.on('click', function() { self.reset(); });
        },
        
        apply: function() {
            var status = this.$status.val();
            var gender = this.$gender.val();
            var sort = this.$sort.val();
            
            var visible = [];
            
            this.$cards.each(function() {
                var $card = $(this);
                var cardStatus = $card.data('status');
                var cardGender = $card.data('gender');
                
                var show = true;
                
                if (status && cardStatus !== status) show = false;
                if (gender && cardGender !== gender) show = false;
                
                if (show) {
                    $card.show();
                    visible.push($card);
                } else {
                    $card.hide();
                }
            });
            
            // Sort
            visible.sort(function(a, b) {
                var $a = $(a), $b = $(b);
                
                switch (sort) {
                    case 'oldest':
                        return new Date($a.data('date')) - new Date($b.data('date'));
                    case 'name-asc':
                        return ($a.data('name') || '').localeCompare($b.data('name') || '');
                    case 'name-desc':
                        return ($b.data('name') || '').localeCompare($a.data('name') || '');
                    default: // newest
                        return new Date($b.data('date')) - new Date($a.data('date'));
                }
            });
            
            // Reorder
            var self = this;
            visible.forEach(function($card) {
                self.$grid.append($card);
            });
            
            // Update count
            this.$count.text(visible.length);
            
            // No results
            if (visible.length === 0 && this.$cards.length > 0) {
                this.$noResults.show();
            } else {
                this.$noResults.hide();
            }
        },
        
        reset: function() {
            this.$status.val('');
            this.$gender.val('');
            this.$sort.val('newest');
            this.apply();
        }
    };
    
    // ===================
    // GALLERY HOVER
    // ===================
    var LFA_Hover = {
        
        init: function() {
            var self = this;
            
            $('.lfa-gallery-hover').each(function() {
                var $wrap = $(this);
                var $imgs = $wrap.find('img');
                var $dots = $wrap.find('.lfa-dots span');
                var count = $imgs.length;
                
                if (count <= 1) return;
                
                var current = 0;
                var interval = null;
                
                function show(idx) {
                    $imgs.removeClass('active').eq(idx).addClass('active');
                    $dots.removeClass('active').eq(idx).addClass('active');
                    current = idx;
                }
                
                function next() {
                    show((current + 1) % count);
                }
                
                $wrap.on('mouseenter', function() {
                    interval = setInterval(next, 1000);
                });
                
                $wrap.on('mouseleave', function() {
                    clearInterval(interval);
                    show(0);
                });
            });
        }
    };
    
    // ===================
    // LIGHTBOX (Single page)
    // ===================
    var LFA_Lightbox = {
        
        images: [],
        current: 0,
        
        init: function() {
            var self = this;
            
            var $lightbox = $('.lfa-lightbox');
            if (!$lightbox.length) return;
            
            var $img = $lightbox.find('img');
            var $counter = $lightbox.find('.lfa-lb-counter');
            
            // Collect images
            $('.lfa-thumb').each(function() {
                var url = $(this).data('full');
                if (url) {
                    self.images.push(url);
                }
            });
            
            if (this.images.length === 0) return;
            
            // Main image click
            $('.lfa-main-photo img').on('click', function() {
                self.open(0);
            });
            
            // Thumb click - update main image
            $('.lfa-thumb').on('click', function() {
                var idx = $(this).index();
                var url = $(this).data('full');
                
                $('.lfa-main-photo img').attr('src', url);
                $('.lfa-thumb').removeClass('active');
                $(this).addClass('active');
            });
            
            // Lightbox controls
            $('.lfa-lb-close').on('click', function() { self.close(); });
            $('.lfa-lb-prev').on('click', function() { self.prev(); });
            $('.lfa-lb-next').on('click', function() { self.next(); });
            
            $lightbox.on('click', function(e) {
                if (e.target === this) self.close();
            });
            
            // Keyboard
            $(document).on('keydown', function(e) {
                if (!$lightbox.hasClass('active')) return;
                if (e.key === 'Escape') self.close();
                if (e.key === 'ArrowLeft') self.prev();
                if (e.key === 'ArrowRight') self.next();
            });
        },
        
        open: function(idx) {
            this.current = idx;
            this.show();
            $('.lfa-lightbox').addClass('active');
            $('body').css('overflow', 'hidden');
        },
        
        close: function() {
            $('.lfa-lightbox').removeClass('active');
            $('body').css('overflow', '');
        },
        
        prev: function() {
            this.current = (this.current - 1 + this.images.length) % this.images.length;
            this.show();
        },
        
        next: function() {
            this.current = (this.current + 1) % this.images.length;
            this.show();
        },
        
        show: function() {
            $('.lfa-lightbox img').attr('src', this.images[this.current]);
            $('.lfa-lb-counter').text((this.current + 1) + ' / ' + this.images.length);
        }
    };
    
    // Initialize
    $(document).ready(function() {
        LFA_Filters.init();
        LFA_Hover.init();
        LFA_Lightbox.init();
    });
    
})(jQuery);
