<?php
/**
 * Provide a documentation area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    ipanorama
 * @subpackage ipanorama/admin/partials
 */
?>
<script>
	var _iPanoramaAppData = window.iPanoramaAppData || {};
	if(_iPanoramaAppData) {
		_iPanoramaAppData.path = '<?php echo plugin_dir_url( dirname(dirname(__FILE__)) ); ?>';
		_iPanoramaAppData.ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
		_iPanoramaAppData.uploadUrl = '<?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl']; ?>';
	}
</script>

<!-- ipnrm-ui-app -->
<div id="ipnrm-ui-app" class="ipnrm-ui-app-settings" x-ng-controller="ngiPanoramaAppController">
	<input type="hidden" id="ipnrm-ui-meta-ui-global-cfg" name="ipnrm-meta-ui-global-cfg" value="<?php echo stripslashes(get_option('ipnrm-meta-ui-global-cfg')); ?>">
	
	<div id="ipnrm-ui-loading" class="ipnrm-ui-loading-main">
		<div class="ipnrm-ui-loading-progress"></div>
	</div>
	<div id="ipnrm-ui-settings" class="ipnrm-ui-clearfix" x-workspace x-init="appData.fn.workspaceSettings.init">
		<div id="ipnrm-ui-tabs" class="ipnrm-ui-clearfix">
			<div class="ipnrm-ui-commands ipnrm-ui-clearfix">
				<div class="ipnrm-ui-cmd-save" x-ng-click="appData.fn.workspaceSettings.updateSettings(appData);"><i class="fa fa-floppy-o fa-fw"></i><span>Update Settings</span></div>
			</div>
			<div class="ipnrm-ui-tab ipnrm-ui-active">General</div>
		</div>
		<div id="ipnrm-ui-tab-data">
			<!-- general section -->
			<div class="ipnrm-ui-section ipnrm-ui-active" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.general.isActive}">
				<div class="ipnrm-ui-config">
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.size}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.size = !appData.config.foldedSections.size;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set panorama custom width and height.<br><br>Note:<br>Also you can set size via shortcode.</div></div>
							<div class="ipnrm-ui-title">Size</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<select class="ipnrm-ui-select" x-ng-model="appData.config.panoramaSize">
									<option value="none">Default</option>
									<option value="fixed">Fixed Size</option>
								</select>
							</div>
							<div x-ng-if="!(appData.config.panoramaSize=='none')"> 
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaWidth">
									<div class="ipnrm-ui-label">Width (auto|value[px,cm,%,etc]|initial|inherit)</div>
								</div>
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaHeight">
									<div class="ipnrm-ui-label">Height (auto|value[px,cm,%,etc]|initial|inherit)</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.theme}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.theme = !appData.config.foldedSections.theme;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Choose a theme from the list.<br><br>Note:<br>You can create your own theme too and put it into the plugin folder for later use.</div></div>
							<div class="ipnrm-ui-title">Theme</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<select class="ipnrm-ui-select" x-ng-model="appData.config.theme">
									<option value="ipnrm-theme-default">default</option>
									<?php 
										$plugin_path = plugin_dir_path( dirname(dirname(__FILE__)) );
										$path = $plugin_path . 'lib/ipanorama.theme.*.css';
										$files = glob( $path );
										foreach($files as $file) {
											$file = strtolower(basename($file));
											$matches = array();
											
											if(preg_match('/^ipanorama.theme.(.*).css?/', $file, $matches)) {
												$theme = $matches[1];
												if($theme !== 'default' ) {
													echo '<option value="ipnrm-theme-' . $theme . '">' . $theme . '</option>';
												}
											}
										}
									?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.imagePreview}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.imagePreview = !appData.config.foldedSections.imagePreview;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set a preview image that will display before the panorama load.</div></div>
							<div class="ipnrm-ui-title">Image Preview</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.imagePreview.isActive"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable image preview.</div></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.imagePreview.isActive">
								<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.config.imagePreview.url}" x-ng-click="appData.fn.selectImage(appData, appData.config.imagePreview);">
									<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.config.imagePreview) + ')'}"></div>
									<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.config.imagePreview);$event.stopPropagation();"></div>
									<div class="ipnrm-ui-image-clear" x-ng-click="appData.config.imagePreview.url=null;$event.stopPropagation();"></div>
									<div class="ipnrm-ui-image-label">Image</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.actions}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.actions = !appData.config.foldedSections.actions;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Use this options to enable or disable different UI actions.</div></div>
							<div class="ipnrm-ui-title">Actions</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.autoLoad"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">If ON the panorama will automatically load.</div></div>
								<div class="ipnrm-ui-label">Auto load</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.autoRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">If ON the panorama will automatically rotate when loaded.</div></div>
								<div class="ipnrm-ui-label">Auto rotate</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.autoRotateSpeed">
								<div class="ipnrm-ui-label">Speed</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" min="0" x-ng-model="appData.config.autoRotateInactivityDelay">
								<div class="ipnrm-ui-label">Inactivity delay (ms)</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelPreventDefault"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable prevention of the default behavior on the mouseWheel event.</div></div>
								<div class="ipnrm-ui-label">Mouse wheel, prevent the default behavior</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the panorama rotate using the mouse scroll wheel.</div></div>
								<div class="ipnrm-ui-label">Mouse wheel rotate</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelRotateCoef">
								<div class="ipnrm-ui-label">Rotate coefficient</div>
							</div>
							
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the panorama zoom using the mouse scroll wheel.</div></div>
								<div class="ipnrm-ui-label">Mouse wheel zoom</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelZoomCoef">
								<div class="ipnrm-ui-label">Zoom coefficient</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.hoverGrab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the "grab/move" mode on the mouse hover event.</div></div>
								<div class="ipnrm-ui-label">Hover grab</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabYawCoef">
								<div class="ipnrm-ui-label">Yaw coefficient</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabPitchCoef">
								<div class="ipnrm-ui-label">Pitch coefficient</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.grab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the "grab/move" mode on the mouse click and move events.</div></div>
								<div class="ipnrm-ui-label">Grab (click and drag)</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.grab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.grabCoef">
								<div class="ipnrm-ui-label">Grab coefficient</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.pinchZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the zoom by pinch gesture.</div></div>
								<div class="ipnrm-ui-label">Pinch zoom</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pinchZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pinchZoomCoef">
								<div class="ipnrm-ui-label">Pinch zoom coefficient</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardNav"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable navigation via keyboard.</div></div>
								<div class="ipnrm-ui-label">Keyboard navigation</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable zoom via keyboard.</div></div>
								<div class="ipnrm-ui-label">Keyboard zoom</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.pitchLimits"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable limits for the pitch parameter.</div></div>
								<div class="ipnrm-ui-label">Pitch limits</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pitchLimits">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pitchLimitsTop">
								<div class="ipnrm-ui-label">Top limit [-90, 90]</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pitchLimits">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pitchLimitsBottom">
								<div class="ipnrm-ui-label">Bottom limit [-90, 90]</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<input class="ipnrm-ui-text" name="ipnrm-input-scene-fade-duration" type="number" min="0" x-ng-model="appData.config.sceneFadeDuration">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specifies the fade duration in milliseconds, when transitioning between scenes.</div></div>
								<div class="ipnrm-ui-label">Scene fade duration (ms)</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.sceneBackgroundLoad"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable loading scene images in background.</div></div>
								<div class="ipnrm-ui-label">Scene images background load</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mobile"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the animation in the mobile browsers.</div></div>
								<div class="ipnrm-ui-label">Mobile animation</div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.controls}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.controls = !appData.config.foldedSections.controls;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Use this options to show or hide controls.</div></div>
							<div class="ipnrm-ui-title">Controls</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showControlsOnHover"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show controls on mouse hover event.</div></div>
								<div class="ipnrm-ui-label">Show controls on hover</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneThumbsCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the thumbnails slider control.</div></div>
								<div class="ipnrm-ui-label">Thumbnails slider control</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div class="ipnrm-ui-radio">
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="true">
									<div class="ipnrm-ui-label">Vertical type</div>
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="false">
									<div class="ipnrm-ui-label">Horizontal type</div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneThumbsStatic"></div>
								<div class="ipnrm-ui-label">Thumbnails are static</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneMenuCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the toggle thumbnails slider control.</div></div>
								<div class="ipnrm-ui-label">Toggle thumbnails slider control</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneNextPrevCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the scene next/prev control.</div></div>
								<div class="ipnrm-ui-label">Scene next/prev control</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.showSceneNextPrevCtrl">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneNextPrevLoop"></div>
								<div class="ipnrm-ui-label">Scene next/prev loop</div>
							</div>
							
							<!--
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showShareCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the share control.</div></div>
								<div class="ipnrm-ui-label">Share control</div>
							</div>
							-->
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showZoomCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the zoom control.</div></div>
								<div class="ipnrm-ui-label">Zoom control</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showFullscreenCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the fullscreen control.</div></div>
								<div class="ipnrm-ui-label">Fullscreen control</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showAutoRotateCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the auto rotate control.</div></div>
								<div class="ipnrm-ui-label">AutoRotate control</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.compass"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the compass control.</div></div>
								<div class="ipnrm-ui-label">Compass</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.title"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the title control.</div></div>
								<div class="ipnrm-ui-label">Title</div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.popover}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.popover = !appData.config.foldedSections.popover;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Use this options to set common popover settings.</div></div>
							<div class="ipnrm-ui-title">Popover Settings</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.popover"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable popovers.</div></div>
								<div class="ipnrm-ui-label">Show Popovers</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set a custom popover HTML template.<br><br>Note:<br>We recommend do not change this parameter without having some knowledge.</div></div>
								<div class="ipnrm-ui-label">HTML template</div>
								<textarea class="ipnrm-ui-textarea" cols="40" rows="5" x-ng-model="appData.config.popoverTemplate"></textarea>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set default placement property of popovers</div></div>
								<select class="ipnrm-ui-select" x-ng-model="appData.config.popoverPlacement">
									<option value="top">top</option>
									<option value="bottom">bottom</option>
									<option value="left">left</option>
									<option value="right">right</option>
									<option value="top-left">top-left</option>
									<option value="top-right">top-right</option>
									<option value="bottom-left">bottom-left</option>
									<option value="bottom-right">bottom-right</option>
								</select>
								<div class="ipnrm-ui-label">Popover placement</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.hotSpotBelowPopover"></div>
								<div class="ipnrm-ui-label">Popover is under the hotspot</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label">Popover show trigger</div><br>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify how a popover will be triggered.</div></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerHover"></div>
								<div class="ipnrm-ui-label">Hover</div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerClick"></div>
								<div class="ipnrm-ui-label">Click</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label">Popover hide trigger</div><br>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify how a popover will be hidden.</div></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerLeave"></div>
								<div class="ipnrm-ui-label">Leave</div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerClick"></div>
								<div class="ipnrm-ui-label">Click</div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerGrab"></div>
								<div class="ipnrm-ui-label">Grab</div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerManual"></div>
								<div class="ipnrm-ui-label">Manual</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify a CSS animation class for the popover show</div></div>
								<button class="ipnrm-ui-button" type="button" x-ng-click="appData.fn.selectPopoverShowClass(appData)">GET</button>
								<input class="ipnrm-ui-text" type="text" x-ng-model="appData.config.popoverShowClass" x-ng-model-options="{updateOn: 'change blur'}">
								<div class="ipnrm-ui-label">Popover Show CSS3 Class</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify a CSS animation class for the popover hide</div></div>
								<button class="ipnrm-ui-button" type="button" x-ng-click="appData.fn.selectPopoverHideClass(appData)">GET</button>
								<input class="ipnrm-ui-text" type="text" x-ng-model="appData.config.popoverHideClass" x-ng-model-options="{updateOn: 'change blur'}">
								<div class="ipnrm-ui-label">Popover Hide CSS3 Class</div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.customCSS}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.customCSS = !appData.config.foldedSections.customCSS;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enter any custom css you want to apply on this virtual tour.<br><br>Note:<br>Please do not use <b>&lt;style&gt;...&lt;/style&gt;</b> tag with Custom CSS.</div></div>
							<div class="ipnrm-ui-title">Custom CSS</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.customCSS"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable custom css rules.</div></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.customCSS">
								<textarea class="ipnrm-ui-textarea" cols="40" rows="20" placeholder="Custom CSS Content" x-ng-model="appData.config.customCSSContent"></textarea>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.customJS}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.customJS = !appData.config.foldedSections.customJS;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enter any custom javascript code you want to execute after the ipanorama load.<br><br>Note:<br>Please do not use <b>&lt;script&gt;...&lt;/script&gt;</b> tag with Custom JavaScript.</div></div>
							<div class="ipnrm-ui-title">Custom JavaScript</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.customJS"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable a custom js source code. The code is hooked to the onload event.</div></div>
								<div class="ipnrm-ui-label">Handle 'onLoad' event</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.customJS">
								<textarea class="ipnrm-ui-textarea" cols="40" rows="20" placeholder="Custom JS code" x-ng-model="appData.config.customJSContent"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
	<div class="ipnrm-ui-modals">
	</div>
</div>
<!-- /end ipnrm-ui-app -->