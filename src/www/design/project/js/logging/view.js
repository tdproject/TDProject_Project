$(function() {
	
    // listener when the minutes to book change
    $('#toBook').change(function() {
    	calculateDate($('#from').val(), $('#toBook').val());
    });
	
    // listener when the from date changes
    $('#from').change(function() {
    	calculateMinutes($('#from').val(), $('#until').val());
    });

    // listener when the target date changes
    $('#until').change(function() {
    	calculateMinutes($('#from').val(), $('#until').val());
    });
    
});

/**
 * Calculate the target date for the source date and the passed minutes.
 * 
 * @param string sourceDate The source date
 * @param minutesToAdd The minutes to add
 * @returns {Boolean} Always FALSE
 */
function calculateDate(sourceDate, minutesToAdd) {

    $.ajax({
        type: "GET",
        url: "?path=/logging/json",
        data: "method=calculateDate&sourceDate=" + sourceDate + "&minutesToAdd=" + minutesToAdd,
        success: function(data) {
            $('#until').val(jQuery.parseJSON(data).value);
        }
    });

    return false;
}

/**
 * Calculates the difference of the passed dates in minutes and
 * set the values in the to book/account fields.
 *  
 * @param string sourceDate The source date
 * @param string targetDate The target date
 * @returns {Boolean} Always FALSE
 */
function calculateMinutes(sourceDate, targetDate) {

    $.ajax({
        type: "GET",
        url: "?path=/logging/json",
        data: "method=calculateMinutes&sourceDate=" + sourceDate + "&targetDate=" + targetDate,
        success: function(data) {
            $('#toBook').val(jQuery.parseJSON(data).value);
            $('#toAccount').val(jQuery.parseJSON(data).value);
        }
    });

    return false;
}