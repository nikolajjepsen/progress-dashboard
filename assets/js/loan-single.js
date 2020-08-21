$(function() {
    $(".config-entry button[name='doUpdate']").on('click', function (e) {
        e.preventDefault();

        const parentEntry = $(this).closest('.config-entry');
        const parentEntryInputs = { id: parentEntry.data('id') };
        parentEntry.find('input').each(function() {
            parentEntryInputs[$(this).attr('name')] = $(this).val();
        });

        $.ajax({
            url: '../../../../app/ajax/loan-single.php?task=updateConfigEntry',
            type: 'post',
            data: parentEntryInputs,
            success: function(e) {
                if (e == 'ok') {
                    parentEntry.fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                } else {
                    alert('error');
                }
            }
        });
        console.log(parentEntryInputs);
    });
});