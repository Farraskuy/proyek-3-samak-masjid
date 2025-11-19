document.addEventListener('DOMContentLoaded', function () {
    // simple drag & drop from toolbox to canvas
    document.querySelectorAll('.fb-toolbox .fb-tool').forEach(function (tool) {
        tool.draggable = true;
        tool.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('text/plain', tool.dataset.type);
        });
    });

    var canvas = document.getElementById('fb-canvas');
    if (canvas) {
        canvas.addEventListener('dragover', function (e) { e.preventDefault(); });
        canvas.addEventListener('drop', function (e) {
            e.preventDefault();
            var type = e.dataTransfer.getData('text/plain');
            addFieldBlock(type || 'text');
        });
    }

    // add field button
    var addBtns = document.querySelectorAll('.fb-add-field');
    addBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            addFieldBlock(btn.dataset.type || 'text');
        });
    });

    function addFieldBlock(type) {
        var container = document.getElementById('fb-canvas');
        if (!container) return;
        var idx = container.querySelectorAll('.fb-field').length;
        var wrapper = document.createElement('div');
        wrapper.className = 'fb-field border p-2 mb-2';
        wrapper.innerHTML = "<div class=\"d-flex justify-content-between\">" +
            "<strong>" + type + "</strong> <button class=\"fb-remove btn btn-sm btn-danger\">Remove</button></div>" +
            "<div class=\"mb-2\"><label>Label</label> <input class=\"form-control fb-label\" name=\"label\" value=\"Field " + (idx+1) + "\"></div>" +
            "<div class=\"mb-2\"><label>Name</label> <input class=\"form-control fb-name\" name=\"name\" value=\"field_" + idx + "\"></div>" +
            "<div class=\"mb-2\"><label>Type</label> <select class=\"form-control fb-type\"><option value=\"text\">Text</option><option value=\"textarea\">Textarea</option><option value=\"select\">Select</option><option value=\"radio\">Radio</option><option value=\"checkbox\">Checkbox</option><option value=\"email\">Email</option></select></div>" +
            "<div class=\"mb-2\"><label>Placeholder</label> <input class=\"form-control fb-placeholder\" name=\"placeholder\"></div>" +
            "<div class=\"mb-2\"><label>Options (comma separated, for select/radio/checkbox)</label> <input class=\"form-control fb-options\" name=\"options\"></div>" +
            "<div class=\"form-check mb-2\"><input type=\"checkbox\" class=\"form-check-input fb-required\" id=\"req_"+idx+"\"><label class=\"form-check-label\" for=\"req_"+idx+"\">Required</label></div>";

        container.appendChild(wrapper);

        wrapper.querySelector('.fb-remove').addEventListener('click', function () {
            wrapper.remove();
        });
    }

    // preview
    var previewBtn = document.getElementById('fb-preview');
    if (previewBtn) {
        previewBtn.addEventListener('click', function () {
            var canvas = document.getElementById('fb-canvas');
            var fields = gatherFields();
            var preview = document.getElementById('fb-preview-area');
            preview.innerHTML = '';
            var form = document.createElement('form');
            fields.forEach(function (f) {
                var wrap = document.createElement('div');
                wrap.className = 'mb-3';
                var label = document.createElement('label');
                label.textContent = f.label;
                wrap.appendChild(label);
                var input;
                if (f.type === 'textarea') {
                    input = document.createElement('textarea');
                    input.className = 'form-control';
                    input.placeholder = f.placeholder || '';
                    input.name = f.name;
                } else if (f.type === 'select') {
                    input = document.createElement('select');
                    input.className = 'form-control';
                    f.options.forEach(function (o) {
                        var opt = document.createElement('option'); opt.value = o; opt.text = o; input.appendChild(opt);
                    });
                    input.name = f.name;
                } else if (f.type === 'radio' || f.type === 'checkbox') {
                    input = document.createElement('div');
                    f.options.forEach(function (o) {
                        var id = 'opt_' + f.name + '_' + o;
                        var sens = document.createElement('div');
                        sens.className = 'form-check';
                        var inn = document.createElement('input');
                        inn.className = 'form-check-input';
                        inn.type = (f.type === 'radio') ? 'radio' : 'checkbox';
                        inn.name = f.name + ((f.type === 'checkbox') ? '[]' : '');
                        inn.id = id; inn.value = o;
                        var lab = document.createElement('label'); lab.className = 'form-check-label'; lab.htmlFor = id; lab.textContent = o;
                        sens.appendChild(inn); sens.appendChild(lab);
                        input.appendChild(sens);
                    });
                } else {
                    input = document.createElement('input');
                    input.type = f.type || 'text';
                    input.className = 'form-control';
                    input.placeholder = f.placeholder || '';
                    input.name = f.name;
                }
                wrap.appendChild(input);
                form.appendChild(wrap);
            });
            preview.appendChild(form);
        });
    }

    // save: serialize fields into hidden input and submit
    var saveBtn = document.getElementById('fb-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var fields = gatherFields();
            var input = document.querySelector('input[name="fields_json"]');
            if (!input) {
                input = document.createElement('input'); input.type = 'hidden'; input.name = 'fields'; input.value = JSON.stringify(fields);
                document.getElementById('fb-form').appendChild(input);
            } else {
                input.value = JSON.stringify(fields);
            }
            document.getElementById('fb-form').submit();
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
            var placeholder = r.querySelector('.fb-placeholder').value;
            var options = r.querySelector('.fb-options').value.split(',').map(function (s) { return s.trim(); }).filter(Boolean);
            var is_required = r.querySelector('.fb-required').checked;
            res.push({label: label, name: name, type: type, placeholder: placeholder, options: options, is_required: is_required});
        });
        return res;
    }
});
