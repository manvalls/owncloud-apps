<?php
  /** @var array $_ */
  /** @var OCP\IURLGenerator $urlGenerator */
  $urlGenerator = $_['urlGenerator'];
  $downloadLink = $_['downloadLink'];
  $fileId = $_['fileId'];
  $scope = $_['scope'];
  $cursor = $_['cursor'];
  $defaults = $_['defaults'];
  $preferences = $_['preferences'];
  $metadata = $_['metadata'];
  $revision = '0017';
  $version = \OCP\App::getAppVersion('files_reader') . '.' . $revision;
    error_log("file_id: " . $fileId);

  /* Owncloud currently does not implement CSPv3, remove this test when it does */
  $nonce = class_exists('\OC\Security\CSP\ContentSecurityPolicyNonceManager')
    ? \OC::$server->getContentSecurityPolicyNonceManager()->getNonce()
    : 'nonce_not_implemented';
?>

<html dir="ltr">

<head class="session" data-downloadlink='<?php print_unescaped($downloadLink);?>' data-fileid='<?php print_unescaped($fileId);?>' data-basepath='<?php p($urlGenerator->linkTo('files_reader',''));?>' data-scope='<?php print_unescaped($scope);?>' data-cursor='<?php print_unescaped($cursor);?>' data-defaults='<?php print_unescaped($defaults);?>' data-preferences='<?php print_unescaped($preferences);?>' data-metadata='<?php print_unescaped($metadata);?>'>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <base href="<?php p($urlGenerator->linkTo('files_reader',''));?>">
    <title>
        <?php p($_['title']);?>
    </title>
    <link rel="shortcut icon" href="img/book.png">
    <link rel="stylesheet" href="vendor/cbrjs/cbr.css?v=<?php p($version) ?>">
    <script type="text/javascript" nonce="<?php p($nonce) ?>" src="<?php p($urlGenerator->linkTo('files_reader', 'js/lib/Blob.js')) ?>?v=<?php p($version) ?>">
    </script>
    <script type="text/javascript" nonce="<?php p($nonce) ?>" src="<?php p($urlGenerator->linkTo('files_reader', 'vendor/epubjs/libs/jquery.min.js')) ?>?v=<?php p($version) ?>">
    </script>

    <script type="text/javascript" nonce="<?php p($nonce) ?>" src="<?php p($urlGenerator->linkTo('files_reader', 'vendor/sindresorhus/screenfull.js')) ?>?v=<?php p($version) ?>">
    </script>
  <script type="text/javascript" nonce="<?php p($nonce) ?>" src="<?php p($urlGenerator->linkTo('files_reader', 'vendor/bitjs/archive.js')) ?>?v=<?php p($version) ?>">
    </script>
    <script type="text/javascript" nonce="<?php p($nonce) ?>" src="<?php p($urlGenerator->linkTo('files_reader', 'vendor/cbrjs/cbr.js')) ?>?v=<?php p($version) ?>">
    </script>

    <script type="text/javascript" nonce="<?php p($nonce) ?>" src="<?php p($urlGenerator->linkTo('files_reader', 'js/ready.js')) ?>?v=<?php p($version) ?>">
    </script>
</head>

<body>
    <!-- data -->
    <!-- /data -->

    <!-- loading progressbar -->
    <div id="progressbar" style="display:none;">
        <span class="progress"><span class="bar"></span></span>
        <br>
        <div class="message"><span class="message-icons"><span class="icon-cloud_download"></span><span class="icon-unarchive"></span></span> <span class="message-text"></span></div>
    </div>
    <!-- /loading progressbar -->

    <!-- toolbar -->
    <div class="toolbar control" name="toolbar">
        <div class="pull-left">
            <button data-trigger="click" data-action="openSidebar" title="open sidebar" class="icon-menu"></button>
        </div>

        <div class="metainfo">
            <span class="book-title"></span>&nbsp;<span class="current-page"></span> / <span class="page-count"></span>
        </div>

        <div class="pull-right">
            <div>
                <button data-trigger="click" data-action="toggleLayout" title="toggle one/two pages at a time" class="icon-single_page_mode layout layout-single"></button>
                <button data-trigger="click" data-action="toggleLayout" title="toggle one/two pages at a time" class="icon-double_page_mode layout layout-double"></button>
            </div>
            <div>
                <button data-trigger="click" data-action="zoomOut" title="zoom out" class="icon-zoom_out"></button>
            </div>
            <div>
                <button data-trigger="click" data-action="zoomIn" title="zoom in" class="icon-zoom_in"></button>
            </div>
            <div>
                <button data-trigger="click" data-action="fitWidth" title="fit page to window width" class="icon-icon-fit-width"></button>
            </div>
            <div>
                <button data-trigger="click" data-action="fitWindow" title="fit page to window" class="icon-icon-fit-window"></button>
            </div>
            <div>
                <button data-trigger="click" data-action="toggleReadingMode" title="switch reading direction" class="icon-format_textdirection_l_to_r manga-false"></button>
                <button data-trigger="click" data-action="toggleReadingMode" title="switch reading direction" class="icon-format_textdirection_r_to_l manga-true"></button>
            </div>
            <div>
                <button data-trigger="click" data-action="toggleFullscreen" title="toggle fullscreen" class="icon-fullscreen fullscreen-false"></button>
                <button data-trigger="click" data-action="toggleFullscreen" title="toggle fullscreen" class="icon-fullscreen_exit fullscreen-true"></button>
            </div>
            <div class="hide close separator"></div>
            <div class="hide close">
                <button data-trigger="click" data-action="close" title="close" class="icon-exit"></button>
            </div>
        </div>
    </div>
    <!-- /toolbar -->

    <!-- loading overlay -->
    <div id="cbr-loading-overlay" class="cbr-control control overlay" name="loadingOverlay" style="display:none"></div>
    <!-- /loading overlay -->

    <!-- busy overlay -->
    <div id="cbr-busy-overlay" class="cbr-control control overlay" name="busyOverlay" style="display:none"></div>
    <!-- /busy overlay -->

    <!-- navigation -->
    <div data-trigger="click" data-action="navigation" data-navigate-side="left" class="cbr-control navigate navigate-left control" name="navigateLeft">
        <span class="icon-navigate_before"></span>
    </div>
    <div data-trigger="click" data-action="toggleToolbar" class="toggle-controls control" name="toggleToolbar"></div>
    <div data-trigger="click" data-action="navigation" data-navigate-side="right" class="cbr-control navigate navigate-right control" name="navigateRight">
        <span class="icon-navigate_next"></span>
    </div>
    <!-- /navigation -->

    <!-- inline progressbar -->
    <div id="cbr-status" class="cbr-control control" name="progressbar" style="display:none">
        <div id="cbr-progress-bar">
            <div class="progressbar-value"></div>
        </div>
    </div>
    <!-- /inline progressbar -->

    <!-- sidebar -->
    <div class="sidebar control" name="sidebar">
        <div class="panels">
            <div class="pull-left">
                <button data-trigger="click" data-action="showToc" title="show table of contents" class="icon-format_list_numbered toc-view open"></button>
                <button data-trigger="click" data-action="showBookSettings" title="show book settings" class="icon-rate_review book-settings-view"></button>
                <button data-trigger="click" data-action="showSettings" title="show settings" class="icon-settings settings-view"></button>
            </div>
            <div class="pull-right">
                <button id="toc-populate" data-trigger="click" data-action="tocPopulate" title="generate thumbnails" class="icon-sync" style="display:none"></button>
                <button data-trigger="click" data-action="closeSidebar" title="close sidebar" class="icon-menu"></button>
            </div>
        </div>
        <div class="toc-view view open">
            <ul id="toc">
            </ul>
        </div>
        <div class="book-settings-view view">
            <div class="metadata">
                <table>
                    <tr>
                        <td>Title:</td><td class="book-title"></td>
                    </tr>
                    <tr>
                        <td>Format:</td><td class="book-format"></td>
                    </tr>
                    <tr>
                        <td>Page count:</td><td class="book-pagecount"></td>
                    </tr>
                    <tr>
                        <td>Size:</td><td class="book-size"></td>
                    </tr>
                </table>
            </div>
            <div class="settings-container" name="enhancements" id="enhancements">
                <label for="enhancements">Image enhancements</label>
                <form name="image-enhancements" data-trigger="reset" data-action="resetEnhancements">
                    <div class="sliders">
                        <div class="control-group">
                            <label title="adjust brightness" class="icon-brightness_low"></label>
                            <input id="brightness" data-trigger="change" data-action="brightness" type="range" min="-100" max="100" step="1" value="0">
                        </div>
                        <div class="control-group">
                            <label title="adjust contrast" class="icon-contrast"></label>
                            <input id="contrast" data-trigger="change" data-action="brightness" type="range" min="-1" max="1" step="0.1" value="0">
                        </div>
                        <div class="control-group">
                            <label title="sharpen" class="icon-droplet"></label>
                            <input id="sharpen" data-trigger="change" data-action="sharpen" type="range" min="0" max="1" step="0.1" value="0">
                        </div>
                    </div>
                    <div class="control-group pull-left">
                        <input id="image-desaturate" type="checkbox" data-trigger="change" data-action="desaturate">
                        <label for="image-desaturate">desaturate</label>
                        <input id="image-removenoise" type="checkbox" data-trigger="change" data-action="removenoise">
                        <label for="image-removenoise">remove noise</label>
                    </div>
                    <div class="control-group pull-right">
                        <input type="reset" value="reset">
                    </div>
                </form>
            </div>
        </div>
        <div class="settings-view view">
            <div class="settings-container" name="settings" id="thumbnail-settings">
                <label for="thumbnail-settings">Thumbnails</label>
                <form name="settings" data-trigger="reset" data-action="resetSettings">
                    <div class="control-group pull-left">
                        <input id="thumbnail-generate" data-trigger="change" data-action="thumbnails" type="checkbox">
                        <label for="thumbnail-generate">Use thumbnails in index </label>
                        <input id="thumbnail-width" data-trigger="change" data-action="thumbnailWidth" type="number" min="50" max="500" step="10" value="200" >
                        <label for="thumbnail-width">Thumbnail width</label>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- /sidebar -->
    <canvas id="viewer" style="display:none;"></canvas>
</body>

</html>
