document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('fb-canvas');
    const emptyState = canvas.querySelector('.empty-state');

    // Helper to toggle empty state
    function toggleEmptyState() {
        const fields = canvas.querySelectorAll('.fb-field');
        if (fields.length > 0) {
            emptyState.style.display = 'none';
        } else {
            emptyState.style.display = 'flex';
        }
        
        // Update badge count
        const badge = document.querySelector('.badge.bg-light');
        if(badge) badge.textContent = fields.length + ' komponen';
    }

    // 1. Drag & Drop from Toolbox
    document.querySelectorAll('.fb-toolbox .fb-tool').forEach(function (tool) {
        tool.draggable = true;
        
        // Drag start
        tool.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('text/plain', tool.dataset.type);
            e.dataTransfer.effectAllowed = 'copy';
        });

        // Click to add (alternative to drag)
        tool.addEventListener('click', function (e) {
            addFieldBlock(tool.dataset.type);
        });
    });

    if (canvas) {
        canvas.addEventListener('dragover', function (e) { 
            e.preventDefault(); 
            e.dataTransfer.dropEffect = 'copy';
            canvas.style.borderColor = '#22C55E';
        });

        canvas.addEventListener('dragleave', function (e) {
            canvas.style.borderColor = ''; // Reset border
        });

        canvas.addEventListener('drop', function (e) {
            e.preventDefault();
            canvas.style.borderColor = ''; // Reset border
            var type = e.dataTransfer.getData('text/plain');
            if(type) addFieldBlock(type);
        });
    }

    // 2. Add Field Logic
    function addFieldBlock(type) {
        if (!canvas) return;
        
        // Hide empty state
        emptyState.style.display = 'none';

        var idx = canvas.querySelectorAll('.fb-field').length;
        var wrapper = document.createElement('div');
        wrapper.className = 'fb-field p-3 mb-3 position-relative bg-white border rounded';
        
        // Content based on type
        let contentHtml = '';
        let icon = 'fa-font';
        
        switch(type) {
            case 'header': icon = 'fa-heading'; break;
            case 'textarea': icon = 'fa-align-left'; break;
            case 'select': icon = 'fa-list'; break;
            case 'radio': icon = 'fa-check-circle'; break;
            case 'checkbox': icon = 'fa-check-square'; break;
        }

        contentHtml += `
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <div class="d-flex align-items-center text-success">
                    <i class="fas ${icon} me-2"></i>
                    <span class="fw-bold text-uppercase small">${type}</span>
                </div>
                <button type="button" class="fb-remove btn btn-sm btn-light text-danger border-0 rounded-circle" style="width: 30px; height: 30px;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Label</label>
                    <input class="form-control form-control-sm fb-label" name="label" value="Field ${idx+1}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Name (Variable)</label>
                    <input class="form-control form-control-sm fb-name" name="name" value="field_${idx}">
                </div>
                
                <input type="hidden" class="fb-type" value="${type}">

                ${['text', 'textarea', 'email'].includes(type) ? `
                <div class="col-12">
                    <label class="form-label small fw-bold text-muted">Placeholder</label>
                    <input class="form-control form-control-sm fb-placeholder" name="placeholder" placeholder="Contoh: Masukkan nama anda...">
                </div>` : ''}

                ${['select', 'radio', 'checkbox'].includes(type) ? `
                <div class="col-12">
                    <label class="form-label small fw-bold text-muted">Options (pisahkan dengan koma)</label>
                    <input class="form-control form-control-sm fb-options" name="options" placeholder="Option 1, Option 2, Option 3">
                </div>` : ''}

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input fb-required" type="checkbox" id="req_${idx}">
                        <label class="form-check-label small" for="req_${idx}">Wajib Diisi (Required)</label>
                    </div>
                </div>
            </div>
        `;

        wrapper.innerHTML = contentHtml;
        canvas.appendChild(wrapper);
        toggleEmptyState();

        // Remove handler
        wrapper.querySelector('.fb-remove').addEventListener('click', function () {
            wrapper.remove();
            toggleEmptyState();
        });
    }

    // 3. Preview Logic (Modal)
    var previewBtn = document.getElementById('fb-preview');
    if (previewBtn) {
        previewBtn.addEventListener('click', function () {
            var fields = gatherFields();
            var previewArea = document.getElementById('fb-preview-area');
            previewArea.innerHTML = '';
            
            if(fields.length === 0) {
                previewArea.innerHTML = '<p class="text-center text-muted my-4">Belum ada komponen yang ditambahkan.</p>';
            } else {
                var form = document.createElement('form');
                fields.forEach(function (f) {
                    var wrap = document.createElement('div');
                    wrap.className = 'mb-3';
                    
                    if(f.type === 'header') {
                        var h = document.createElement('h4');
                        h.className = 'fw-bold mt-4 mb-2';
                        h.textContent = f.label;
                        wrap.appendChild(h);
                        if(f.placeholder) {
                            var p = document.createElement('p');
                            p.className = 'text-muted';
                            p.textContent = f.placeholder;
                            wrap.appendChild(p);
                        }
                    } else {
                        var label = document.createElement('label');
                        label.className = 'form-label fw-medium';
                        label.textContent = f.label + (f.is_required ? ' *' : '');
                        wrap.appendChild(label);
                        
                        var input;
                        if (f.type === 'textarea') {
                            input = document.createElement('textarea');
                            input.className = 'form-control';
                            input.placeholder = f.placeholder || '';
                        } else if (f.type === 'select') {
                            input = document.createElement('select');
                            input.className = 'form-select';
                            f.options.forEach(function (o) {
                                var opt = document.createElement('option'); opt.value = o; opt.text = o; input.appendChild(opt);
                            });
                        } else if (f.type === 'radio' || f.type === 'checkbox') {
                            input = document.createElement('div');
                            f.options.forEach(function (o) {
                                var id = 'opt_' + f.name + '_' + o.replace(/\s+/g, '_');
                                var sens = document.createElement('div');
                                sens.className = 'form-check';
                                var inn = document.createElement('input');
                                inn.className = 'form-check-input';
                                inn.type = (f.type === 'radio') ? 'radio' : 'checkbox';
                                inn.name = f.name;
                                inn.id = id;
                                var lab = document.createElement('label'); lab.className = 'form-check-label'; lab.htmlFor = id; lab.textContent = o;
                                sens.appendChild(inn); sens.appendChild(lab);
                                input.appendChild(sens);
                            });
                        } else {
                            input = document.createElement('input');
                            input.type = f.type || 'text';
                            input.className = 'form-control';
                            input.placeholder = f.placeholder || '';
                        }
                        
                        if(input) wrap.appendChild(input);
                    }
                    form.appendChild(wrap);
                });
                previewArea.appendChild(form);
            }

            // Show Modal
            var myModal = new bootstrap.Modal(document.getElementById('previewModal'));
            myModal.show();
        });
    }

    // 4. Save Logic
    var saveBtn = document.getElementById('fb-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var fields = gatherFields();
            
            if(fields.length === 0) {
                alert('Mohon tambahkan minimal satu komponen.');
                return;
            }

            var input = document.querySelector('input[name="fields"]'); // Changed name to fields to match controller validation if needed, but controller expects array.
            // Wait, controller expects 'fields' as array.
            // We need to append inputs for each field or send JSON.
            // The controller code: $data['fields'] as array.
            // So we should create hidden inputs for fields array.
            
            var form = document.getElementById('fb-form');
            
            // Clear existing hidden fields
            form.querySelectorAll('input[name^="fields["]').forEach(el => el.remove());

            fields.forEach((f, i) => {
                for (const [key, value] of Object.entries(f)) {
                    let val = value;
                    if(Array.isArray(value)) val = value.join(','); // Simple join for options
                    
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `fields[${i}][${key}]`;
                    input.value = val;
                    form.appendChild(input);
                }
            });

            form.submit();
        });
    }

    function gatherFields() {
        var container = document.getElementById('fb-canvas');
        var rows = container ? container.querySelectorAll('.fb-field') : [];
        var res = [];
        rows.forEach(function (r) {
            var label = r.querySelector('.fb-label').value;
            var name = r.querySelector('.fb-name').value;
            var type = r.querySelector('.fb-type').value;
            
            var placeholderInput = r.querySelector('.fb-placeholder');
            var placeholder = placeholderInput ? placeholderInput.value : '';
            
            var optionsInput = r.querySelector('.fb-options');
            var options = optionsInput ? optionsInput.value.split(',').map(function (s) { return s.trim(); }).filter(Boolean) : [];
            
            var reqInput = r.querySelector('.fb-required');
            var is_required = reqInput ? reqInput.checked : false;
            
            res.push({label: label, name: name, type: type, placeholder: placeholder, options: options, is_required: is_required});
        });
        return res;
    }
});
