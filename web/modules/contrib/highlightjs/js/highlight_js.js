(function ($, Drupal) {
  'use strict';

  /**
   * Init highlight.js.
   *
   * @type {{attach: attach}}
   */
  Drupal.behaviors.highlightJs = {
    attach: function (context, settings) {
      if (typeof hljs !== 'undefined') {
        if (settings.highlightJs.selectMode == 'code') {
          //Process all code tags
          $('code').each(function (i, block) {
            if (block) {
              hljs.highlightBlock(block);
            }
          });
        }
        else {
          // Provide a Drupal-specific wrapper for JUSH Framework.
          // Act on anything that is classed with "jush".
          hljs.initHighlightingOnLoad();
        }
      }
    }
  };

})(jQuery, Drupal);
