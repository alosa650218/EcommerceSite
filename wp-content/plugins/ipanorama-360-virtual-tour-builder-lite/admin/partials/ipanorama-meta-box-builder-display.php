<?php
/**
 * This file is used to markup the meta box aspects of the plugin.
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
<div id="ipnrm-ui-app" class="ipnrm-ui-app-item" x-ng-controller="ngiPanoramaAppController">
	<input type="hidden" id="ipnrm-ui-meta-ui-cfg" name="ipnrm-meta-ui-cfg" value="<?php echo get_post_meta( get_the_ID(), 'ipnrm-meta-ui-cfg', true ); ?>">
	<input type="hidden" id="ipnrm-ui-meta-ui-global-cfg" name="ipnrm-meta-ui-global-cfg" value="<?php echo stripslashes(get_option('ipnrm-meta-ui-global-cfg')); ?>">
	<input type="hidden" id="ipnrm-ui-meta-global-cfg" name="ipnrm-meta-global-cfg" value="">
	<input type="hidden" id="ipnrm-ui-meta-panorama-cfg" name="ipnrm-meta-panorama-cfg" value="">
	<input type="hidden" id="ipnrm-ui-meta-panorama-theme" name="ipnrm-meta-panorama-theme" value="">
	<input type="hidden" id="ipnrm-ui-meta-total-scenes" name="ipnrm-meta-total-scenes" value="">
	
	<div id="ipnrm-ui-loading" class="ipnrm-ui-loading-main">
		<div class="ipnrm-ui-loading-progress"></div>
	</div>
	<div id="ipnrm-ui-workspace" class="ipnrm-ui-clearfix" x-workspace x-init="appData.fn.workspace.init">
		<div id="ipnrm-ui-screen">
			<div id="ipnrm-ui-canvas" x-ng-class="{'ipnrm-ui-target-tool': appData.targetTool}"></div>
			<div class="ipnrm-ui-value-info" x-ng-if="appData.scene.selected">
				<div class="ipnrm-ui-value-yaw">yaw: {{appData.scene.selected.yaw}}</div>
				<div class="ipnrm-ui-value-pitch">pitch: {{appData.scene.selected.pitch}}</div>
				<div class="ipnrm-ui-value-zoom">zoom: {{appData.scene.selected.zoom}}</div>
			</div>
		</div>
		<div id="ipnrm-ui-tabs" class="ipnrm-ui-clearfix">
			<div class="ipnrm-ui-commands ipnrm-ui-clearfix">
				<div class="ipnrm-ui-cmd-preview" x-ng-click="appData.fn.preview(appData);"><i class="fa fa-television fa-fw"></i><span>Preview</span></div>
				<div class="ipnrm-ui-cmd-load" x-ng-click="appData.fn.storage.loadFromFile(appData);" title="Load settings from a local config file (json format)"><i class="fa fa-upload fa-fw"></i><span>Load From File</span></div>
				<div class="ipnrm-ui-cmd-save" x-ng-click="appData.fn.storage.saveToFile(appData);" title="Save settings to a local config file (json format)"><i class="fa fa-floppy-o fa-fw"></i><span>Save To File</span></div>
			</div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.general.isActive}" x-tab-panel-item x-id="general" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-cog"></i>General</div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.scenes.isActive}" x-tab-panel-item x-id="scenes" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-picture-o"></i>Scenes<div class="ipnrm-ui-label">{{appData.config.scenes.length}}</div></div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.hotspots.isActive, 'ipnrm-ui-hide': !appData.scene.selected}" x-tab-panel-item x-id="hotspots" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-dot-circle-o"></i>Hotspots<div class="ipnrm-ui-label">{{appData.scene.selected.config.hotspots.length}}</div></div>
			<input id="ipnrm-ui-load-from-file" type="file" style="display:none;" />
		</div>
		<div id="ipnrm-ui-tab-data">
			<!-- general section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.general.isActive}">
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
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.panoramaSize}">
									<div class="ipnrm-ui-label">
										<span>{{ appData.configGlobal.panoramaSize == 'none' ? 'default' : appData.configGlobal.panoramaSize }}</span>
										<span>{{ appData.configGlobal.panoramaSize == 'fixed' ? '[' + appData.configGlobal.panoramaWidth + 'x' + appData.configGlobal.panoramaHeight + ']' : '' }}</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.panoramaSize" title="Check to use global settings"></div>
								</div>
							</div>
							<div x-ng-if="!(appData.config.panoramaSize=='none')"> 
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaWidth">
									<div class="ipnrm-ui-label">Width (auto|value[px,cm,%,etc]|initial|inherit)</div>
									
									<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.panoramaWidth}">
										<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.panoramaWidth }}</span></div>
										<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.panoramaWidth" title="Check to use global settings"></div>
									</div>
								</div>
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaHeight">
									<div class="ipnrm-ui-label">Height (auto|value[px,cm,%,etc]|initial|inherit)</div>
									
									<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.panoramaHeight}">
										<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.panoramaHeight }}</span></div>
										<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.panoramaHeight" title="Check to use global settings"></div>
									</div>
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
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.theme}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.theme.replace('ipnrm-theme-','') }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.theme" title="Check to use global settings"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.imagePreview}">
									<div class="ipnrm-ui-label">
										<span>{{ appData.configGlobal.imagePreview.isActive ? 'ON' : 'OFF' }}</span>
										<span>{{ appData.configGlobal.imagePreview.url ? appData.configGlobal.imagePreview.url : 'none' }}</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.imagePreview" title="Check to use global settings"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoLoad}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoLoad ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoLoad" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.autoRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">If ON the panorama will automatically rotate when loaded.</div></div>
								<div class="ipnrm-ui-label">Auto rotate</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoRotate}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoRotate ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoRotate" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.autoRotateSpeed">
								<div class="ipnrm-ui-label">Speed</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoRotateSpeed}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoRotateSpeed }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoRotateSpeed" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" min="0" x-ng-model="appData.config.autoRotateInactivityDelay">
								<div class="ipnrm-ui-label">Inactivity delay (ms)</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoRotateInactivityDelay}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoRotateInactivityDelay }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoRotateInactivityDelay" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelPreventDefault"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable prevention of the default behavior on the mouseWheel event.</div></div>
								<div class="ipnrm-ui-label">Mouse wheel, prevent the default behavior</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelPreventDefault}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelPreventDefault ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelPreventDefault" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the panorama rotate using the mouse scroll wheel.</div></div>
								<div class="ipnrm-ui-label">Mouse wheel rotate</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelRotate}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelRotate ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelRotate" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelRotateCoef">
								<div class="ipnrm-ui-label">Rotate coefficient</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelRotateCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelRotateCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelRotateCoef" title="Check to use global settings"></div>
								</div>
							</div>
							
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the panorama zoom using the mouse scroll wheel.</div></div>
								<div class="ipnrm-ui-label">Mouse wheel zoom</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelZoom}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelZoom ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelZoom" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelZoomCoef">
								<div class="ipnrm-ui-label">Zoom coefficient</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelZoomCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelZoomCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelZoomCoef" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.hoverGrab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the "grab/move" mode on the mouse hover event.</div></div>
								<div class="ipnrm-ui-label">Hover grab</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hoverGrab}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hoverGrab ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hoverGrab" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabYawCoef">
								<div class="ipnrm-ui-label">Yaw coefficient</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hoverGrabYawCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hoverGrabYawCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hoverGrabYawCoef" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabPitchCoef">
								<div class="ipnrm-ui-label">Pitch coefficient</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hoverGrabPitchCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hoverGrabPitchCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hoverGrabPitchCoef" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.grab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the "grab/move" mode on the mouse click and move events.</div></div>
								<div class="ipnrm-ui-label">Grab (click and drag)</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.grab}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.grab ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.grab" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.grab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.grabCoef">
								<div class="ipnrm-ui-label">Grab coefficient</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.grabCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.grabCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.grabCoef" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.pinchZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the zoom by pinch gesture.</div></div>
								<div class="ipnrm-ui-label">Pinch zoom</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.pinchZoom}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.pinchZoom ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.pinchZoom" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pinchZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pinchZoomCoef">
								<div class="ipnrm-ui-label">Pinch zoom coefficient</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.pinchZoomCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.pinchZoomCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.pinchZoomCoef" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardNav"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable navigation via keyboard.</div></div>
								<div class="ipnrm-ui-label">Keyboard navigation</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.keyboardNav}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.keyboardNav ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.keyboardNav" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable zoom via keyboard.</div></div>
								<div class="ipnrm-ui-label">Keyboard zoom</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.keyboardZoom}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.keyboardZoom ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.keyboardZoom" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.pitchLimits"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable limits for the pitch parameter.</div></div>
								<div class="ipnrm-ui-label">Pitch limits</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.pitchLimits}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.pitchLimits ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.pitchLimits" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pitchLimits">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pitchLimitsTop">
								<div class="ipnrm-ui-label">Top limit [-90, 90]</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.pitchLimitsTop}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.pitchLimitsTop }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.pitchLimitsTop" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pitchLimits">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pitchLimitsBottom">
								<div class="ipnrm-ui-label">Bottom limit [-90, 90]</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.pitchLimitsBottom}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.pitchLimitsBottom }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.pitchLimitsBottom" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<input class="ipnrm-ui-text" name="ipnrm-input-scene-fade-duration" type="number" min="0" x-ng-model="appData.config.sceneFadeDuration">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specifies the fade duration in milliseconds, when transitioning between scenes.</div></div>
								<div class="ipnrm-ui-label">Scene fade duration (ms)</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneFadeDuration}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneFadeDuration }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneFadeDuration" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.sceneBackgroundLoad"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable loading scene images in background.</div></div>
								<div class="ipnrm-ui-label">Scene images background load</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneBackgroundLoad}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneBackgroundLoad ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneBackgroundLoad" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mobile"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable the animation in the mobile browsers.</div></div>
								<div class="ipnrm-ui-label">Mobile animation</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mobile}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mobile ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mobile" title="Check to use global settings"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showControlsOnHover}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showControlsOnHover ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showControlsOnHover" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneThumbsCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the thumbnails slider control.</div></div>
								<div class="ipnrm-ui-label">Thumbnails slider control</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showSceneThumbsCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showSceneThumbsCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showSceneThumbsCtrl" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div class="ipnrm-ui-radio">
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="true">
									<div class="ipnrm-ui-label">Vertical type</div>
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="false">
									<div class="ipnrm-ui-label">Horizontal type</div>
								</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneThumbsVertical}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneThumbsVertical ? 'vertical' : 'horizontal' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneThumbsVertical" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneThumbsStatic"></div>
								<div class="ipnrm-ui-label">Thumbnails are static</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneThumbsStatic}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneThumbsStatic ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneThumbsStatic" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneMenuCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the toggle thumbnails slider control.</div></div>
								<div class="ipnrm-ui-label">Toggle thumbnails slider control</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showSceneMenuCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showSceneMenuCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showSceneMenuCtrl" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneNextPrevCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the scene next/prev control.</div></div>
								<div class="ipnrm-ui-label">Scene next/prev control</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showSceneNextPrevCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showSceneNextPrevCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showSceneNextPrevCtrl" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.showSceneNextPrevCtrl">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneNextPrevLoop"></div>
								<div class="ipnrm-ui-label">Scene next/prev loop</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneNextPrevLoop}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneNextPrevLoop ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneNextPrevLoop" title="Check to use global settings"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showZoomCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showZoomCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showZoomCtrl" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showFullscreenCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the fullscreen control.</div></div>
								<div class="ipnrm-ui-label">Fullscreen control</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showFullscreenCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showFullscreenCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showFullscreenCtrl" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showAutoRotateCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the auto rotate control.</div></div>
								<div class="ipnrm-ui-label">AutoRotate control</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showAutoRotateCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showAutoRotateCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showAutoRotateCtrl" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.compass"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the compass control.</div></div>
								<div class="ipnrm-ui-label">Compass</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.compass}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.compass ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.compass" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.title"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the title control.</div></div>
								<div class="ipnrm-ui-label">Title</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.title}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.title ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.title" title="Check to use global settings"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popover}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popover ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popover" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set a custom popover HTML template.<br><br>Note:<br>We recommend do not change this parameter without having some knowledge.</div></div>
								<div class="ipnrm-ui-label">HTML template</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverTemplate}">
									<div class="ipnrm-ui-label"><span>...</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverTemplate" title="Check to use global settings"></div>
								</div>
								
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverPlacement}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popoverPlacement }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverPlacement" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.hotSpotBelowPopover"></div>
								<div class="ipnrm-ui-label">Popover is under the hotspot</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hotSpotBelowPopover}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hotSpotBelowPopover ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hotSpotBelowPopover" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label">Popover show trigger</div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify how a popover will be triggered.</div></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverShowTrigger}">
									<div class="ipnrm-ui-label">
										<span>
											{{ appData.configGlobal.popoverShowTriggerHover ? 'Hover' : '' }}
											{{ appData.configGlobal.popoverShowTriggerClick ? 'Click' : '' }}
										</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverShowTrigger" title="Check to use global settings"></div>
								</div>
								
								<br>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerHover"></div>
								<div class="ipnrm-ui-label">Hover</div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerClick"></div>
								<div class="ipnrm-ui-label">Click</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label">Popover hide trigger</div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify how a popover will be hidden.</div></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverHideTrigger}">
									<div class="ipnrm-ui-label">
										<span>
											{{ appData.configGlobal.popoverHideTriggerLeave ? 'Leave' : '' }}
											{{ appData.configGlobal.popoverHideTriggerClick ? 'Click' : '' }}
											{{ appData.configGlobal.popoverHideTriggerGrab ? 'Grab' : '' }}
											{{ appData.configGlobal.popoverHideTriggerManual ? 'Manual' : '' }}
										</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverHideTrigger" title="Check to use global settings"></div>
								</div>
								
								<br>
								
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverShowClass}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popoverShowClass ? appData.configGlobal.popoverShowClass : 'none' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverShowClass" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify a CSS animation class for the popover hide</div></div>
								<button class="ipnrm-ui-button" type="button" x-ng-click="appData.fn.selectPopoverHideClass(appData)">GET</button>
								<input class="ipnrm-ui-text" type="text" x-ng-model="appData.config.popoverHideClass" x-ng-model-options="{updateOn: 'change blur'}">
								<div class="ipnrm-ui-label">Popover Hide CSS3 Class</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverHideClass}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popoverHideClass ? appData.configGlobal.popoverHideClass : 'none' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverHideClass" title="Check to use global settings"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.customCSS}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.customCSS ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.customCSS" title="Check to use global settings"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.customJS}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.customJS ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.customJS" title="Check to use global settings"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.customJS">
								<textarea class="ipnrm-ui-textarea" cols="40" rows="20" placeholder="Custom JS code" x-ng-model="appData.config.customJSContent"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /end general section -->
			
			<!-- scene section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.scenes.isActive}">
				<div class="ipnrm-ui-item-list-wrap">
					<div class="ipnrm-ui-item-commands">
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.add(appData)"><i class="fa fa-fw fa-plus-square"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.copySelected(appData)"><i class="fa fa-fw fa-clone"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.upSelected(appData)"><i class="fa fa-fw fa-arrow-up"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.downSelected(appData)"><i class="fa fa-fw fa-arrow-down"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.removeSelected(appData)"><i class="fa fa-fw fa-trash"></i></div>
					</div>
					<ul class="ipnrm-ui-item-list">
						<li class="ipnrm-ui-item" x-ng-repeat="scene in appData.config.scenes track by scene.id" x-ng-class="{'ipnrm-ui-active': scene.isSelected}" x-ng-click="appData.fn.scenes.select(appData, scene)">
							<span class="ipnrm-ui-icon"><i class="fa fa-cube"></i></span>
							<span class="ipnrm-ui-name">{{scene.id}} <i x-ng-if="scene.config.title">| {{scene.config.title}}</i></span>
							<span class="ipnrm-ui-visible" x-ng-click="scene.isVisible=!scene.isVisible" x-ng-class="{'ipnrm-ui-off': !scene.isVisible}"></span>
						</li>
					</ul>
				</div>
				<div class="ipnrm-ui-config">
					<div x-ng-class="{'ipnrm-ui-hide': !appData.scene.selected}">
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.sceneSettings}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.sceneSettings = !appData.config.foldedSections.sceneSettings;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Use this options to set common scene settings.</div></div>
								<div class="ipnrm-ui-title">Scene Settings</div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set title for selected scene.</div></div>
									<input class="ipnrm-ui-text ipnrm-ui-long" type="text" placeholder="Scene title" x-ng-model="appData.scene.selected.config.title">
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.scene.selected.config.titleHtml"></div>
									<div class="ipnrm-ui-label">The title has HTML markup</div>
								</div>
								
								<!--
									<div class="ipnrm-ui-label">Selector</div>
									<div class="ipnrm-ui-control">
										<input type="text" class="ipnrm-ui-long" x-ng-model="appData.scene.selected.config.titleSelector" placeholder="it allows you to set an element's HTML content for the title">
									</div>
								-->
								
								<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Select a type of selected scene and set images.</div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.scene.selected.config.type">
										<option value="sphere">sphere</option>
										<option value="cylinder">cylinder</option>
										<option value="cube">cube</option>
									</select>
									<div class="ipnrm-ui-label">Scene type</div><br>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.type == 'cube'">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Select count of textures for a cube scene.</div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.scene.selected.config.cubeTextureCount">
										<option value="single">1 Texture</option>
										<option value="six">6 Textures</option>
									</select>
									<div class="ipnrm-ui-label">Texture count</div><br>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageFront.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageFront);">
										<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageFront) + ')'}"></div>
										<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageFront);$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageFront.url=null;$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-label">Front</div>
									</div>
									
									<span x-ng-class="{'ipnrm-ui-image-hide': appData.scene.selected.config.type != 'cube' || appData.scene.selected.config.cubeTextureCount != 'six'}">
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageBack.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageBack);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageBack) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageBack);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageBack.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label">Back</div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageLeft.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageLeft);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageLeft) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageLeft);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageLeft.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label">Left</div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageRight.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageRight);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageRight) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageRight);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageRight.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label">Right</div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageTop.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageTop);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageTop) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageTop);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageTop.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label">Top</div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageBottom.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageBottom);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageBottom) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageBottom);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageBottom.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label">Bottom</div>
										</div>
									</span>
								</div>
								
								<div class="ipnrm-ui-accordion" x-ng-if="appData.scene.selected.config.type == 'sphere'">
									<div class="ipnrm-ui-accordion-toggle">Advanced Options</div>
									<div class="ipnrm-ui-accordion-data">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set the sphere mesh quality.<br><br>Note:<br>This is a number of horizontal and vertical segments.</div></div>
											
											<div class="ipnrm-ui-inline-group">
												<div class="ipnrm-ui-label">Width segments</div>
												<div class="ipnrm-ui-control">
													<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.scene.selected.config.sphereWidthSegments" x-ng-change="appData.hotspot.dirty=true">
												</div>
											</div>
											<div class="ipnrm-ui-inline-group">
												<div class="ipnrm-ui-label">Height segments</div>
												<div class="ipnrm-ui-control">
													<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.scene.selected.config.sphereHeightSegments" x-ng-change="appData.hotspot.dirty=true">
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.scene.selected.config.thumb"></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set a thumbnail image for selected scene..</div></div>
									<div class="ipnrm-ui-label">Scene thumb image</div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.thumb">
									<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.thumbImage.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.thumbImage);">
										<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.thumbImage) + ')'}"></div>
										<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.thumbImage);$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.thumbImage.url=null;$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-label">Image</div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set the panorama's starting yaw and pitch position in degrees, and the zoom parameter.</div></div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Yaw</div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.yaw">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.yaw=appData.scene.selected.yaw"></div>{{appData.scene.selected.yaw}}</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Pitch</div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.pitch">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.pitch=appData.scene.selected.pitch"></div>{{appData.scene.selected.pitch}}</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Zoom</div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.zoom">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.zoom=appData.scene.selected.zoom"></div>{{appData.scene.selected.zoom}}</div>
										</div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set the north offset for compass direction, in degrees.<br><br>Note:<br>As this affects the compass, it only has an effect if compass is set to ON.</div></div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Compass Offset</div><br>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.compassNorthOffset">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.compassNorthOffset=appData.scene.selected.yaw"></div>{{appData.scene.selected.yaw}}</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /end scene section -->
			
			<!-- hotspots section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.hotspots.isActive}">
				<div class="ipnrm-ui-item-list-wrap">
					<div class="ipnrm-ui-item-commands">
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.add(appData)"><i class="fa fa-fw fa-plus-square"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.copySelected(appData)"><i class="fa fa-fw fa-clone"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.upSelected(appData)"><i class="fa fa-fw fa-arrow-up"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.downSelected(appData)"><i class="fa fa-fw fa-arrow-down"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.removeSelected(appData)"><i class="fa fa-fw fa-trash"></i></div>
					</div>
					<ul class="ipnrm-ui-item-list">
						<li class="ipnrm-ui-item" x-ng-repeat="hotspot in appData.scene.selected.config.hotspots track by hotspot.id" x-ng-class="{'ipnrm-ui-active': hotspot.isSelected}" x-ng-click="appData.fn.hotspots.select(appData, hotspot)">
							<span class="ipnrm-ui-icon"><i class="fa fa-thumb-tack"></i></span>
							<span class="ipnrm-ui-name">{{hotspot.id}}</span>
							<span class="ipnrm-ui-visible" x-ng-click="hotspot.isVisible=!hotspot.isVisible;appData.hotspot.dirty=true" x-ng-class="{'ipnrm-ui-off': !hotspot.isVisible}"></span>
						</li>
					</ul>
				</div>
				<div class="ipnrm-ui-config">
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotTargetTool}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotTargetTool = !appData.config.foldedSections.hotspotTargetTool;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Use target tool to quick create a hotspot and it's location on the panorama.</div></div>
							<div class="ipnrm-ui-title">Target Tool</div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.targetTool"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable target tool.<br><br>Note:<br>When the target tool is ON click on the panorama together with Ctrl Key (for Mac with Meta Key).</div></div>
							</div>
						</div>
					</div>
					<div x-ng-class="{'ipnrm-ui-hide': !appData.hotspot.selected}">
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotLocation}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotLocation = !appData.config.foldedSections.hotspotLocation;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set a hotspot location.</div></div>
								<div class="ipnrm-ui-title">Hotspot Location</div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set the hotspot's starting yaw and pitch position in degrees.<br><br>Note:<br>If you want to change the location of the selected hotspot, just click on the panorama together with Ctrl Key (for Mac with Meta Key).</div></div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Yaw</div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.hotspot.selected.config.yaw" x-ng-change="appData.hotspot.dirty=true">
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Pitch</div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.hotspot.selected.config.pitch" x-ng-change="appData.hotspot.dirty=true">
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotSettings}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotSettings = !appData.config.foldedSections.hotspotSettings;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set common hotspot settings.</div></div>
								<div class="ipnrm-ui-title">Hotspot Settings</div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Specify the ID of the scene to link to.</div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.hotspot.selected.config.sceneId">
										<option value="none">none</option>
										<option x-ng-repeat="scene in appData.config.scenes track by scene.id" value="{{appData.fn.getSceneKeyById(scene.id)}}">{{scene.id}} {{appData.fn.trunc(scene.config.title, 25)}}</option>
									</select>
									<div class="ipnrm-ui-label">Go to the scene</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set a hotspot image, otherwise the plugin uses a theme icon.</div></div>
									<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.hotspot.selected.config.image.url}" x-ng-click="appData.fn.selectImage(appData, appData.hotspot.selected.config.image);">
										<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.hotspot.selected.config.image) + ')'}"></div>
										<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.hotspot.selected.config.image);$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-clear" x-ng-click="appData.hotspot.selected.config.image.url=null;$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-label">Image</div>
									</div>
								</div>
								
								<div x-ng-if="(appData.hotspot.selected.config.image.url ? true : false)">
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Image Custom Width (px)</div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.hotspot.selected.config.image.width">
										</div>
									</div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Image Custom Height (px)</div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.hotspot.selected.config.image.height">
										</div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">This lets you connect a hotspot to an internet address.</div></div>
									<input class="ipnrm-ui-text ipnrm-ui-long" type="text" placeholder="Url" x-ng-model="appData.hotspot.selected.config.link">
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.linkNewWindow"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin">Open url in new window</div>
								</div>
								
								<div class="ipnrm-ui-accordion">
									<div class="ipnrm-ui-accordion-toggle">Advanced Options</div>
									<div class="ipnrm-ui-accordion-data">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable hotspot custom style.<br><br>Note:<br>You can define your own style for hotspot with images, icons, text and etc..</div></div>
											<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.hotspot.selected.config.custom"></div>
											<div class="ipnrm-ui-label">Custom style</div>
										</div>
										
										<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.custom">
											<input class="ipnrm-ui-text ipnrm-ui-long" type="text" placeholder="Hotspot Class Name" x-ng-model="appData.hotspot.selected.config.customClassName">
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set custom classes for a hotspot element.</div></div>
										</div>
										
										<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.custom">
											<textarea class="ipnrm-ui-textarea" cols="40" rows="5" placeholder="Hotspot HTML Content" x-ng-model="appData.hotspot.selected.config.customContent"></textarea>
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set content for a hotspot element, if you want to make it complex.</div></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotPopover}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotPopover = !appData.config.foldedSections.hotspotPopover;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set common hotspot popover settings.</div></div>
								<div class="ipnrm-ui-title">Popover Settings</div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.hotspot.selected.config.popover"></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Enable/disable hotspot popover window.</div></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div class="ipnrm-ui-label">Popover Content</div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set content for a popover window.</div></div>
									<textarea class="ipnrm-ui-textarea" cols="40" rows="5" x-ng-model="appData.hotspot.selected.config.popoverContent"></textarea>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.popoverHtml"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin">The popover content has HTML markup</div>
								</div>
								
								<!--
								<div class="ipnrm-ui-label">Selector</div>
								<div class="ipnrm-ui-control">
									<input type="text" class="ipnrm-ui-long" x-ng-model="appData.hotspot.selected.config.popoverSelector" placeholder="it allows you to set an element's HTML content for the popover">
								</div>
								-->
								
								<!--
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.popoverLazyload"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin">Lazyload</div>
								</div>
								-->
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.popoverShow"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin">Show on load</div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div class="ipnrm-ui-label">Popover Placement</div><br>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Set a placement property for a popover window.</div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.hotspot.selected.config.popoverPlacement">
										<option value="default">default</option>
										<option value="top">top</option>
										<option value="bottom">bottom</option>
										<option value="left">left</option>
										<option value="right">right</option>
										<option value="top-left">top-left</option>
										<option value="top-right">top-right</option>
										<option value="bottom-left">bottom-left</option>
										<option value="bottom-right">bottom-right</option>
									</select>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div class="ipnrm-ui-label">Popover Custom Width (px)</div><br>
									<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.hotspot.selected.config.popoverWidth">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /end hotspots section -->
		</div>
	</div>
	<div class="ipnrm-ui-modals">
	</div>
	<div id="ipnrm-ui-preview-wrap" x-ng-class="{'ipnrm-ui-active': appData.preview}">
		<button type="button" id="ipnrm-ui-preview-close" aria-label="Close" x-ng-click="appData.fn.previewClose(appData);"><span aria-hidden="true">&times;</span></button>
		<div id="ipnrm-ui-preview-inner">
			<style x-ng-if="appData.config.customCSS">
				{{appData.config.customCSSContent}}
			</style>
			<div id="ipnrm-ui-preview-canvas">
			</div>
		</div>
	</div>
</div>
<!-- /end ipnrm-ui-app -->