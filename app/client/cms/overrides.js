(function($) {
    $.entwine('ss', function($) {
        // Adjust 'Forms' title
        $('#Menu-App-Admin-FormAdmin .text').text('Leads & Forms');

        // Setup date range DateFields (via flatpickr)
        $('.form-group.is-date-range').entwine({
            onmatch: function() {
                var $field = $(this);
                var $start = $field.find('input.date-range--start');
                var $end = $field.find('input.date-range--end');

                setTimeout(function() {
                    var endPicker = $end[0]._flatpickr;

                    $start.on('change', function() {
                        $start = $field.find('input.date-range--start');
                        $end = $field.find('input.date-range--end');
                        var startDate = endPicker.parseDate(
                            $start.val(),
                            'Y-m-d'
                        );
                        var endDate = endPicker.parseDate($end.val(), 'Y-m-d');
                        endPicker.set('minDate', startDate);

                        if (endDate < startDate) {
                            endPicker.setDate(startDate);
                        }
                    });
                }, 1000);
            },
        });
    });
})(jQuery);
