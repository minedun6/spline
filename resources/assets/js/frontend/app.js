
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

$(function() {
    $('#js-grid-lightbox-gallery').cubeportfolio({
        filters: '#js-filters-lightbox-gallery1, #js-filters-lightbox-gallery2',
        loadMore: '#js-loadMore-lightbox-gallery',
        loadMoreAction: 'click',
        layoutMode: 'grid',
        mediaQueries: [{
            width: 1500,
            cols: 5
        }, {
            width: 1100,
            cols: 4
        }, {
            width: 800,
            cols: 3
        }, {
            width: 480,
            cols: 2
        }, {
            width: 320,
            cols: 1
        }],
        defaultFilter: '*',
        animationType: 'rotateSides',
        gapHorizontal: 10,
        gapVertical: 10,
        gridAdjustment: 'responsive',
        caption: 'zoom',
        displayType: 'sequentially',
        displayTypeSpeed: 100,

        // lightbox
        lightboxDelegate: '.cbp-lightbox',
        lightboxGallery: true,
        lightboxTitleSrc: 'data-title',
        lightboxCounter: '<div class="cbp-popup-lightbox-counter">{{current}} of {{total}}</div>',

        // singlePageInline
        singlePageInlineDelegate: '.cbp-singlePageInline',
        singlePageInlinePosition: 'below',
        singlePageInlineInFocus: true
    });
    $('#js-grid-mosaic').cubeportfolio({
          filters: '#js-filters-mosaic',
          loadMore: '#js-loadMore-mosaic',
          loadMoreAction: 'click',
          layoutMode: 'mosaic',
          sortToPreventGaps: true,
          mediaQueries: [{
              width: 1500,
              cols: 5
          }, {
              width: 1100,
              cols: 4
          }, {
              width: 800,
              cols: 3
          }, {
              width: 480,
              cols: 2
          }, {
              width: 320,
              cols: 1
          }],
          defaultFilter: '*',
          animationType: 'quicksand',
          gapHorizontal: 0,
          gapVertical: 0,
          gridAdjustment: 'responsive',
          caption: 'zoom',
          displayType: 'sequentially',
          displayTypeSpeed: 100,

          // lightbox
          lightboxDelegate: '.cbp-lightbox',
          lightboxGallery: true,
          lightboxTitleSrc: 'data-title',
          lightboxCounter: '<div class="cbp-popup-lightbox-counter">{{current}} of {{total}}</div>',
      });
			$('.js-grid-juicy-projects').cubeportfolio({
       filters: '#js-filters-juicy-projects',
       loadMore: '#js-loadMore-juicy-projects',
       loadMoreAction: 'click',
       layoutMode: 'grid',
       defaultFilter: '*',
       animationType: 'quicksand',
       gapHorizontal: 35,
       gapVertical: 30,
       gridAdjustment: 'responsive',
       mediaQueries: [{
           width: 1500,
           cols: 5
       }, {
           width: 1100,
           cols: 4
       }, {
           width: 800,
           cols: 3
       }, {
           width: 480,
           cols: 2
       }, {
           width: 320,
           cols: 1
       }],
       caption: 'overlayBottomReveal',
       displayType: 'sequentially',
       displayTypeSpeed: 80,

       // lightbox
       lightboxDelegate: '.cbp-lightbox',
       lightboxGallery: true,
       lightboxTitleSrc: 'data-title',
       lightboxCounter: '<div class="cbp-popup-lightbox-counter">@{{current}} of @{{total}}</div>',

       // singlePage popup
       singlePageDelegate: '.cbp-singlePage',
       singlePageDeeplinking: true,
       singlePageStickyNavigation: true,
       singlePageCounter: '<div class="cbp-popup-singlePage-counter">@{{current}} of @{{total}}</div>',
       singlePageCallback: function(url, element) {
           // to update singlePage content use the following method: this.updateSinglePage(yourContent)
           var t = this;

           $.ajax({
                   url: url,
                   type: 'GET',
                   dataType: 'html',
                   timeout: 10000
               })
               .done(function(result) {
                   t.updateSinglePage(result);
               })
               .fail(function() {
                   t.updateSinglePage('AJAX Error! Please refresh the page!');
               });
       },
   });
});	