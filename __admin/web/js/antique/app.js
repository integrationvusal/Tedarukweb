$(document).ready(function () {

    _bodyWrapHeight = $('#body-wrapper').outerHeight();
    _windowHeight = $(window).outerHeight();
    if(_bodyWrapHeight < _windowHeight){
        $('.content-box').css( 'min-height', ($('.content-box').outerHeight() + (_windowHeight - _bodyWrapHeight )) );
    } 

    $('.description-remove-file, .comment-remove-file, .official-comment-remove-file').on('click', function (e) {
        e.preventDefault();
        var that = $(this);

        if (!that.hasClass('disabled')) {

            $.confirm({
                title: i18n.commentFileDeleteConfirmTitle,
                content: i18n.commentFileDeleteConfirmText,
                confirmButton: i18n.commentFileDeleteConfirmButton,
                cancelButton: i18n.commentFileDeleteCancelButton,
                keyboardEnabled: true,
                confirm: function () {
                    var parentUl = that.parent().parent().parent().parent();
                    var parentLi = that.parent().parent().parent();

                    var fileId = that.attr('data-file-id');
                    that.addClass('disabled');

                    // clearing errors
                    parentUl.find('.error-on-file-remove').remove();

                    $.ajax({
                        url: baseUrl + '/' + lang + '/case/removefile',
                        async: true,
                        cache: false,
                        dataType: 'json',
                        method: 'post',
                        data: {
                            'id': fileId,
                            'type': that.attr('data-context'),
                            '_csrf': _csrf
                        },
                        success: function (response, status, xhr) {
                            if (response.success) {
                                parentLi.remove();

                                // if parent ul tag has no li children any more, then remove it
                                if (parentUl.find('li.comment-attachment-item').length == 0) {
                                    parentUl.remove();
                                }
                            } else {
                                parentUl.append('<p class="error-on-file-remove"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;' + response.message + '</p>');
                            }

                            that.removeClass('disabled');
                        },
                        error: function (xhr, err, descr) {
                        }
                    });
                },
                cancel: function () {
                }
            });
        }
    });
    // removing comment's file   end

    var citySelectBox = $('#js-city-select-box');
    var regionSelectBox = $('#js-region-select-box');
    var schoolSelectBox = $('#js-school-select-box');
    var advisorSelectBox = $('#js-advisor-select-box');

    /* getting school list via AJAX on region selectbox changed start */
    citySelectBox.on('change', function () {
        var cityId = $(this).val();

        // disabling regionSelectBox
        disable(regionSelectBox);
        resetSelect(regionSelectBox);
        // disabling schoolSelectBox
        disable(schoolSelectBox);
        resetSelect(schoolSelectBox);
        // disabling advisorSelectBox
        disable(advisorSelectBox);
        resetSelect(advisorSelectBox);

        if (isInt(cityId)) {
            $.get(baseUrl + '/' + lang + '/location/get_city_regions?city_id=' + cityId, function (data, status) {
                if (data.success == true && !jQuery.isEmptyObject(data.data)) {

                    // generating options...
                    var options = '<option>- - - - - -</option>';
                    $.each(data.data, function (i, v) {
						options += '<option value="' + v.id + '">' + v.name + '</option>\r\n'
                    });

                    // inserting generated options into schoolSelectBox
                    regionSelectBox.html(options);

                    // make it enable
                    enable(regionSelectBox);
                }
            });
        } else {
            // choosed default '- - - - -' option, so need to remove all options, disabled it & add default option
            disable(regionSelectBox);
            disable(schoolSelectBox);
            disable(advisorSelectBox);
            resetSelect(regionSelectBox);
            resetSelect(schoolSelectBox);
            resetSelect(advisorSelectBox);
        }
    });
    /* getting school list via AJAX on region selectbox changed   end */

    /* getting school list via AJAX on region selectbox changed start */
    regionSelectBox.on('change', function () {
        var regionId = $(this).val();
        console.log('asdasdas');

        // disabling schoolSelectBox
        disable(schoolSelectBox);
        resetSelect(schoolSelectBox);
        // disabling advisorSelectBox
        disable(advisorSelectBox);
        resetSelect(advisorSelectBox);

        if (isInt(regionId)) {
            $.get(baseUrl + '/' + lang + '/location/getschool?region_id=' + regionId, function (data, status) {
                if (data.success == true && !jQuery.isEmptyObject(data.data)) {

                    // generating options...
                    var options = '<option>- - - - - -</option>';
                    $.each(data.data, function (i, v) {
                        options += '<option value="' + v.id + '">' + v.name + '</option>\r\n'
                    });

                    // inserting generated options into schoolSelectBox
                    schoolSelectBox.html(options);

                    // make it enable
                    enable(schoolSelectBox);
                }
            });
        } else {
            // choosed default '- - - - -' option, so need to remove all options, disabled it & add default option
            disable(schoolSelectBox);
            disable(advisorSelectBox);
            resetSelect(schoolSelectBox);
            resetSelect(advisorSelectBox);
        }
    });
    /* getting school list via AJAX on region selectbox changed   end */

    /* getting advisors list via AJAX on school selectbox shanged start */
    schoolSelectBox.on('change', function () {
        var schoolId = $(this).val();
        var regionId = regionSelectBox.val();

        // disabling advisorSelectBox
        disable(advisorSelectBox);

        if (isInt(regionId)) {
            $.get(baseUrl + '/' + lang + '/advisor/getadvisorslist?school_id=' + schoolId + '&region_id=' + regionId, function (data, status) {
                if (data.success == true && !jQuery.isEmptyObject(data.data)) {

                    // generating options...
                    var options = '<option value="">- - - - - -</option>';
                    //var options = '';
                    $.each(data.data, function (i, v) {
                        options += '<option value="' + v.id + '">' + v.name + '</option>\r\n'
                    });

                    // inserting generated options into schoolSelectBox
                    advisorSelectBox.html(options);

                    // make it enable
                    enable(advisorSelectBox);
                }
            });
        } else {
            // choosed default '- - - - -' option, so need to remove all options, disabled it & add default option
            disable(advisorSelectBox);
        }
    });
    /* getting advisors list via AJAX on school selectbox shanged   end */


    /* Showing confirmation alerts on case delete/restore attempt start */
    var casesDeleteRestoreBtn = $('[data-action]');
    var formForCaseDelete = $('#js-delete-case-form');

    casesDeleteRestoreBtn.on('click', function () {
        var that = $(this);

        $.confirm({
            title: i18n.commentFileDeleteConfirmTitle,
            content: (that.attr('data-action') == 'delete') ? i18n.commentFileDeleteConfirmText : i18n.commentFileRestoreConfirmText,
            confirmButton: i18n.commentFileDeleteConfirmButton,
            cancelButton: i18n.commentFileDeleteCancelButton,
            keyboardEnabled: true,
            confirm: function () {
                var action = that.attr('data-action');
                var id = that.attr('data-id');

                formForCaseDelete
                    .find('[name=action]')
                    .val(action)
                    .end()
                    .find('[name=id]')
                    .val(id)
                    .end()
                    .submit()
                ;
            },
            cancel: function () {
            }
        });
    });

    /* Showing confirmation alerts on case delete/restore attempt   end */

    // delay fade out start
    var objForDelayedFadeOut = $('.delay-fade-out');
    if(objForDelayedFadeOut.length){
        setTimeout(objForDelayedFadeOut.fadeOut(100), 3000);
    }
    // delay fade out   end


    /* utils start */
    function isInt(value) {
        return !isNaN(value) &&
            parseInt(Number(value)) == value && !isNaN(parseInt(value, 10));
    }

    function enable(jqObj) {
        jqObj.prop('disabled', false);
    }

    function disable(jqObj) {
        jqObj.prop('disabled', true);
    }

    function resetSelect(jqObj) {
        //jqObj.val(jqObj.find('option:first'));
        jqObj.find('option:first').attr('selected', 'selected');
    }
	
    /* utils   end */
});
function reloadWithQueryStringVars (queryStringVars) {
	var existingQueryVars = location.search ? location.search.substring(1).split("&") : [],
		currentUrl = location.search ? location.href.replace(location.search,"") : location.href,
		newQueryVars = {},
		newUrl = currentUrl + "?";
	if(existingQueryVars.length > 0) {
		for (var i = 0; i < existingQueryVars.length; i++) {
			var pair = existingQueryVars[i].split("=");
			newQueryVars[pair[0]] = pair[1];
		}
	}
	if(queryStringVars) {
		for (var queryStringVar in queryStringVars) {
			newQueryVars[queryStringVar] = queryStringVars[queryStringVar];
		}
	}
	if(newQueryVars) { 
		for (var newQueryVar in newQueryVars) {
			newUrl += newQueryVar + "=" + newQueryVars[newQueryVar] + "&";
		}
		newUrl = newUrl.substring(0, newUrl.length-1);
		window.location.href = newUrl;
	} else {
		window.location.href = location.href;
	}
}