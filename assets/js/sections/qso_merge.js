$(function () {
    if ($('#merge_form').length === 0) {
        return;
    }

    function gatherFormData() {
        const keeperInput = $('input[name="keeper_id"]:checked');
        const keeper_id = keeperInput.length ? keeperInput.val() : null;
        const field_sources = {};
        $('.field-source:checked').each(function () {
            const name = $(this).attr('name');
            const match = name && name.match(/^field_sources\[(.+)]$/);
            if (match) {
                field_sources[match[1]] = $(this).val();
            }
        });
        return {
            ids: $('#merge_ids').val(),
            keeper_id: keeper_id,
            field_sources: field_sources,
        };
    }

    function formatPreview(preview) {
        const lines = [];
        Object.keys(preview).sort().forEach(function (key) {
            let value = preview[key];
            if (value === null || value === '') {
                value = '—';
            }
            lines.push(key + ': ' + value);
        });
        return lines.join('\n');
    }

    $('#merge_preview_btn').on('click', function () {
        const payload = gatherFormData();
        if (!payload.keeper_id) {
            return;
        }
        $.post(base_url + 'index.php/search/merge_preview', payload, function (response) {
            if (response.ok) {
                $('#merge_preview_output').text(formatPreview(response.preview));
            } else {
                $('#merge_preview_output').text(response.message || 'Preview failed.');
            }
        }, 'json').fail(function () {
            $('#merge_preview_output').text('Preview failed.');
        });
    });

    $('#merge_confirm_btn').on('click', function () {
        const payload = gatherFormData();
        if (!payload.keeper_id) {
            return;
        }
        const message = typeof merge_lang_confirm !== 'undefined' ? merge_lang_confirm : 'Confirm merge?';
        if (!window.confirm(message)) {
            return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true);
        $.post(base_url + 'index.php/search/merge_confirm', payload, function (response) {
            if (response.ok && response.redirect) {
                window.location.href = response.redirect;
            } else {
                alert(response.message || 'Merge failed.');
                $btn.prop('disabled', false);
            }
        }, 'json').fail(function () {
            alert('Merge failed.');
            $btn.prop('disabled', false);
        });
    });

    $('#merge_preview_btn').trigger('click');
});
