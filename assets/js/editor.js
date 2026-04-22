/**
 * Photo to Elementor Editor Integration
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        // Wait for Elementor to be ready
        if (typeof elementor !== 'undefined') {
            initializePhotoToElementor();
        } else {
            $(document).on('elementor:init', initializePhotoToElementor);
        }
    });

    function initializePhotoToElementor() {
        // Add button to Elementor panel
        addPhotoToElementorButton();

        // Initialize modal
        createPhotoModal();
    }

    function addPhotoToElementorButton() {
        // Find the Elementor panel header
        const panelHeader = $('.elementor-panel-menu-item-add-new');

        if (panelHeader.length) {
            // Add our button after the "Add New" button
            panelHeader.after(`
                <div class="elementor-panel-menu-item photo-to-elementor-btn" id="photo-to-elementor-trigger">
                    <i class="eicon-photo-library" aria-hidden="true"></i>
                    <span class="elementor-panel-menu-item-title">Photo to UI</span>
                </div>
            `);

            // Bind click event
            $('#photo-to-elementor-trigger').on('click', openPhotoModal);
        }
    }

    function createPhotoModal() {
        // Create modal HTML
        const modalHtml = `
            <div id="photo-to-elementor-modal" class="photo-to-elementor-modal" style="display: none;">
                <div class="photo-modal-overlay"></div>
                <div class="photo-modal-content">
                    <div class="photo-modal-header">
                        <h3>Convert Photo to Elementor Layout</h3>
                        <button class="photo-modal-close">&times;</button>
                    </div>
                    <div class="photo-modal-body">
                        <div class="photo-upload-area" id="photo-upload-area">
                            <div class="photo-upload-placeholder">
                                <i class="eicon-photo-library"></i>
                                <p>Drag & drop an image here or click to browse</p>
                                <input type="file" id="photo-file-input" accept="image/*" style="display: none;">
                            </div>
                        </div>
                        <div class="photo-preview" id="photo-preview" style="display: none;">
                            <img id="photo-preview-img" src="" alt="Preview">
                            <button class="photo-remove-btn" id="photo-remove-btn">Remove</button>
                        </div>
                        <div class="photo-options">
                            <label for="photo-prompt">Additional Instructions (optional):</label>
                            <textarea id="photo-prompt" placeholder="e.g., Make it mobile-first, use blue theme, etc."></textarea>
                        </div>
                    </div>
                    <div class="photo-modal-footer">
                        <button class="photo-generate-btn" id="photo-generate-btn" disabled>
                            <span class="btn-text">Generate Layout</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="eicon-loading"></i> Analyzing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHtml);

        // Bind modal events
        bindModalEvents();
    }

    function bindModalEvents() {
        const modal = $('#photo-to-elementor-modal');
        const uploadArea = $('#photo-upload-area');
        const fileInput = $('#photo-file-input');
        const preview = $('#photo-preview');
        const previewImg = $('#photo-preview-img');
        const generateBtn = $('#photo-generate-btn');
        const removeBtn = $('#photo-remove-btn');

        // Open modal
        function openPhotoModal() {
            modal.show();
        }

        // Close modal
        $('.photo-modal-close, .photo-modal-overlay').on('click', function () {
            modal.hide();
            resetModal();
        });

        // Upload area click
        uploadArea.on('click', function () {
            fileInput.click();
        });

        // File input change
        fileInput.on('change', function (e) {
            handleFileSelect(e.target.files[0]);
        });

        // Drag and drop
        uploadArea.on('dragover dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('drag-over');
        });

        uploadArea.on('dragleave dragend', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('drag-over');
        });

        uploadArea.on('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('drag-over');

            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });

        // Remove image
        removeBtn.on('click', function () {
            resetModal();
        });

        // Generate layout
        generateBtn.on('click', function () {
            generateLayout();
        });

        // Make openPhotoModal available globally
        window.openPhotoModal = openPhotoModal;
    }

    function handleFileSelect(file) {
        if (!file || !file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }

        // Resize image before processing
        resizeImage(file, 1024, 1024, function (resizedFile) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const base64 = e.target.result.split(',')[1]; // Remove data:image/... prefix
                showImagePreview(e.target.result);
                $('#photo-generate-btn').prop('disabled', false);
                // Store base64 for later use
                window.selectedImageData = base64;
            };
            reader.readAsDataURL(resizedFile);
        });
    }

    /**
     * Resize image to reduce API costs and improve performance
     */
    function resizeImage(file, maxWidth, maxHeight, callback) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        img.onload = function () {
            // Calculate new dimensions
            let { width, height } = img;

            if (width > height) {
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = (width * maxHeight) / height;
                    height = maxHeight;
                }
            }

            // Set canvas size
            canvas.width = width;
            canvas.height = height;

            // Draw resized image
            ctx.drawImage(img, 0, 0, width, height);

            // Convert to blob
            canvas.toBlob(function (blob) {
                const resizedFile = new File([blob], file.name, {
                    type: file.type,
                    lastModified: Date.now()
                });
                callback(resizedFile);
            }, file.type, 0.8); // 0.8 quality for JPEG
        };

        img.src = URL.createObjectURL(file);
    }

    function showImagePreview(src) {
        $('#photo-upload-area').hide();
        $('#photo-preview').show();
        $('#photo-preview-img').attr('src', src);
    }

    function resetModal() {
        $('#photo-upload-area').show();
        $('#photo-preview').hide();
        $('#photo-file-input').val('');
        $('#photo-prompt').val('');
        $('#photo-generate-btn').prop('disabled', true);
        window.selectedImageData = null;
    }

    function generateLayout() {
        if (!window.selectedImageData) {
            alert('Please select an image first.');
            return;
        }

        const generateBtn = $('#photo-generate-btn');
        const btnText = generateBtn.find('.btn-text');
        const btnLoading = generateBtn.find('.btn-loading');

        // Show loading state
        generateBtn.prop('disabled', true);
        btnText.hide();
        btnLoading.show();

        // Show skeleton loading state in Elementor
        showSkeletonLoading();

        const prompt = $('#photo-prompt').val();

        // Make API request
        $.ajax({
            url: photoToElementor.apiEndpoint,
            method: 'POST',
            data: {
                image: window.selectedImageData,
                prompt: prompt,
            },
            headers: {
                'X-WP-Nonce': photoToElementor.nonce
            },
            success: function (response) {
                if (response.success && response.data) {
                    injectLayout(response.data);
                    $('#photo-to-elementor-modal').hide();
                    resetModal();
                } else {
                    hideSkeletonLoading();
                    alert('Failed to generate layout. Please try again.');
                }
            },
            error: function (xhr, status, error) {
                hideSkeletonLoading();
                console.error('API Error:', error);
                alert('Error generating layout. Please check your API key and try again.');
            },
            complete: function () {
                // Reset button state
                generateBtn.prop('disabled', false);
                btnText.show();
                btnLoading.hide();
            }
        });
    }

    /**
     * Show skeleton loading placeholders in Elementor
     */
    function showSkeletonLoading() {
        if (typeof elementor === 'undefined') {
            return;
        }

        try {
            const previewView = elementor.getPreviewView();
            if (!previewView) {
                return;
            }

            // Create skeleton placeholder elements
            const skeletonElements = [
                {
                    id: 'skeleton-' + Math.random().toString(36).substr(2, 7),
                    elType: 'container',
                    isInner: false,
                    settings: {
                        _background_background: 'classic',
                        _background_color: '#f0f0f0',
                        _padding: {
                            unit: 'px',
                            top: '40',
                            right: '20',
                            bottom: '40',
                            left: '20',
                            isLinked: true
                        }
                    },
                    elements: [
                        {
                            id: 'skeleton-heading-' + Math.random().toString(36).substr(2, 7),
                            elType: 'widget',
                            widgetType: 'heading',
                            isInner: false,
                            settings: {
                                title: 'Analyzing layout...',
                                size: 'h2',
                                align: 'center',
                                _typography_font_family: 'Inter',
                                _typography_font_weight: '600'
                            },
                            elements: []
                        },
                        {
                            id: 'skeleton-text-' + Math.random().toString(36).substr(2, 7),
                            elType: 'widget',
                            widgetType: 'text-editor',
                            isInner: false,
                            settings: {
                                editor: '<p style="color: #666; text-align: center;">AI is processing your image...</p>',
                                align: 'center'
                            },
                            elements: []
                        }
                    ]
                }
            ];

            // Add skeleton elements
            skeletonElements.forEach(function (element) {
                previewView.addChildElement(element, {}, true);
            });

            // Store skeleton element IDs for cleanup
            window.skeletonElements = skeletonElements.map(el => el.id);

        } catch (error) {
            console.error('Error showing skeleton loading:', error);
        }
    }

    /**
     * Hide skeleton loading placeholders
     */
    function hideSkeletonLoading() {
        if (!window.skeletonElements || typeof elementor === 'undefined') {
            return;
        }

        try {
            const previewView = elementor.getPreviewView();
            if (!previewView) {
                return;
            }

            // Remove skeleton elements
            window.skeletonElements.forEach(function (elementId) {
                const element = previewView.children.find(function (child) {
                    return child.get('id') === elementId;
                });
                if (element) {
                    element.destroy();
                }
            });

            window.skeletonElements = [];

        } catch (error) {
            console.error('Error hiding skeleton loading:', error);
        }
    }

    /**
     * Inject the generated layout into Elementor
     */
    function injectLayout(elementorJson) {
        if (typeof elementor === 'undefined') {
            return;
        }

        try {
            // Get the current section/container
            const previewView = elementor.getPreviewView();
            if (!previewView) {
                return;
            }

            // Add each element to the current container
            elementorJson.forEach(function (element) {
                previewView.addChildElement(element, {}, true);
            });

            // Refresh the preview
            elementor.reloadPreview();

        } catch (error) {
            console.error('Error injecting layout:', error);
            alert('Error injecting layout into Elementor. Please try again.');
        }
    }

})(jQuery);