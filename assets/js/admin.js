/**
 * Lost & Found Animal - Admin JS
 *
 * @author  Wojtek Kobylecki / Bella Design Studio
 * @version 1.0.5
 */

(function($) {
    'use strict';

    var LFA_Gallery = {
        frame: null,
        $container: null,
        $input: null,

        init: function() {
            this.$container = $('#lfa-gallery-images');
            this.$input = $('#lfa_gallery');
            if (!this.$container.length || !this.$input.length) return;
            this.bindEvents();
        },

        bindEvents: function() {
            var self = this;
            $('#lfa-add-images').on('click', function(e) {
                e.preventDefault();
                self.openMediaFrame();
            });
            $(document).on('click', '.lfa-remove-image', function(e) {
                e.preventDefault();
                e.stopPropagation();
                self.removeImage($(this).closest('.lfa-gallery-item'));
            });
        },

        openMediaFrame: function() {
            var self = this;
            if (this.frame) {
                this.frame.open();
                return;
            }
            this.frame = wp.media({
                title: 'Select Gallery Images',
                button: { text: 'Add to Gallery' },
                multiple: true,
                library: { type: 'image' }
            });
            this.frame.on('select', function() {
                var selection = self.frame.state().get('selection');
                var currentIds = self.getIds();
                selection.each(function(attachment) {
                    var id = attachment.id;
                    if (currentIds.indexOf(id) !== -1) return;
                    currentIds.push(id);
                    self.addImageElement(attachment);
                });
                self.updateInput(currentIds);
            });
            this.frame.open();
        },

        addImageElement: function(attachment) {
            var url = attachment.attributes.sizes && attachment.attributes.sizes.thumbnail 
                ? attachment.attributes.sizes.thumbnail.url 
                : attachment.attributes.url;
            var html = '<div class="lfa-gallery-item" data-id="' + attachment.id + '">';
            html += '<img src="' + url + '" alt="">';
            html += '<button type="button" class="lfa-remove-image">&times;</button>';
            html += '</div>';
            this.$container.append(html);
        },

        removeImage: function($item) {
            var id = parseInt($item.data('id'), 10);
            var currentIds = this.getIds();
            var index = currentIds.indexOf(id);
            if (index > -1) currentIds.splice(index, 1);
            this.updateInput(currentIds);
            $item.fadeOut(200, function() { $(this).remove(); });
        },

        getIds: function() {
            var val = this.$input.val();
            if (!val || val.trim() === '') return [];
            return val.split(',').map(function(id) {
                return parseInt(id.trim(), 10);
            }).filter(function(id) {
                return !isNaN(id) && id > 0;
            });
        },

        updateInput: function(ids) {
            this.$input.val(ids.join(','));
        }
    };

    $(document).ready(function() {
        LFA_Gallery.init();
    });
})(jQuery);
