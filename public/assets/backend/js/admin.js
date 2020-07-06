jQuery(document).ready(function($){
	ADMIN.back();
	ADMIN.checkAllItem();
	ADMIN.deleteItem();
	ADMIN.restoreItem();
    ADMIN.clickAllChecked();
    ADMIN.clickOneCheckedRoute();

    ADMIN.getListDictrictId();
	ADMIN.getListWardId();
	ADMIN.f5GetListWardId();

    ADMIN.clickSearchDrop();
    ADMIN.tooltip();

    ADMIN.clickAddAttime();
    ADMIN.clickRemoveAttime();
});

ADMIN = {
	deleteItem:function(){
		jQuery('a#deleteMoreItem').click(function(){
			var total = jQuery( "input:checked" ).length;
			if(total==0){
				jAlert('Vui lòng chọn ít nhất 1 bản ghi để xóa!', 'Thông báo');
				return false;
			}else{
				jConfirm('Bạn muốn xóa [OK]:Đồng ý [Cancel]:Bỏ qua?)', 'Xác nhận', function(r) {
					if(r){
						jQuery('form#formListItem').submit();
						return true;
					}
				});
				return false;
			}
		});
	},
	restoreItem:function(){
		jQuery('a#restoreMoreItem').click(function(){
			var total = jQuery( "input:checked" ).length;
			if(total==0){
				jAlert('Vui lòng chọn ít nhất 1 bản ghi để khôi phục!', 'Thông báo');
				return false;
			}else{
				jConfirm('Bạn muốn khôi phục [OK]:Đồng ý [Cancel]:Bỏ qua?)', 'Xác nhận', function(r) {
					if(r){
						jQuery('form#formListItem').attr("action", BASE_URL+"admin/trash/restore");
                        jQuery('form#formListItem').submit();
						return true;
					}
				});
				return false;
			}
		});
	},
	back:function(){
		jQuery("button[type=reset]").click(function(){
	   		window.history.back();
	   });
	},
	checkAllItem:function(){
		jQuery("input#checkAll").click(function(){
            var checkedStatus = this.checked;
            jQuery("input.checkItem").each(function(){
                this.checked = checkedStatus;
            });
        });
	},
	checkAllClass:function(strs){
		if(strs != ''){
			jQuery("input." + strs).click(function(){
				var checkedStatus = this.checked;
				jQuery("input.item_" + strs).each(function(){
					this.checked = checkedStatus;
				});
			});
		}
	},
	clickAllChecked:function(){
		jQuery(".btnClickAllAction").click(function(){
			 var checkBoxes = $("input[class*='item_']");
		         checkBoxes.prop("checked", !checkBoxes.prop("checked"));
		});
	},

    //P D W
    getListDictrictId:function(){
        jQuery('#listProviceId').change(function(){
            var proviceId = $(this).val();
            var _token = $('input[name="_token"]').val();
            if(proviceId > -1){
                var url = BASE_URL+'admin/dictrict/ajaxGetDictrictByProvice';
                jQuery('#listDictrictId').html('');
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: "proviceId="+encodeURI(proviceId) + '&_token='+_token,
                    success: function(data){
                        if(data != ''){
                            data = jQuery.parseJSON(data);
                            jQuery('#listDictrictId').append(data);
                            return false;
                        }
                    }
                });
            }
        });
        //Ward edit
        var proviceId = $('#listProviceId').val();
        var dictrictId = $('#listDictrictId').attr('data');
        var _token = $('input[name="_token"]').val();
        if(proviceId > -1){
            var url = BASE_URL+'admin/dictrict/ajaxGetDictrictByProvice';
            jQuery('#listDictrictId, #listDictrictId').html('');
            jQuery.ajax({
                type: "POST",
                url: url,
                data: "proviceId="+encodeURI(proviceId) + "&dictrictId="+encodeURI(dictrictId) + '&_token='+_token,
                success: function(data){
                    if(data != ''){
                        data = jQuery.parseJSON(data);
                        jQuery('#listDictrictId').append(data);
                        if($('.page-content-box').hasClass('adminOrder')){
                            ADMIN.f5GetListWardId();
                        }
                        return false;
                    }
                }
            });
        }
    },
    getListWardId:function(){
        jQuery('#listDictrictId').change(function(){
            var dictrictId = $(this).val();
            var _token = $('input[name="_token"]').val();
            if(dictrictId > -1){
                var url = BASE_URL+'admin/ward/ajaxGetWardByDictrict';
                jQuery('#listWardId').html('');
                ADMIN.changeDictrictGetPriceShip(dictrictId);
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: "dictrictId="+encodeURI(dictrictId) + '&_token='+_token,
                    success: function(data){
                        if(data != ''){
                            data = jQuery.parseJSON(data);
                            jQuery('#listWardId').append(data);
                            return false;
                        }
                    }
                });
            }
        });
    },
    f5GetListWardId:function(){
        //ward edit
        var dictrictId = $('#listDictrictId').attr('data');
        var wardId = $('#listWardId').attr('data');
        var _token = $('input[name="_token"]').val();
        if(dictrictId > -1){
            var url = BASE_URL+'admin/ward/ajaxGetWardByDictrict';
            jQuery('#listWardId').html('');
            jQuery.ajax({
                type: "POST",
                url: url,
                data: "dictrictId="+encodeURI(dictrictId) + "&wardId="+encodeURI(wardId) + '&_token='+_token,
                success: function(data){
                    if(data != ''){
                        data = jQuery.parseJSON(data);
                        jQuery('#listWardId').append(data);
                        return false;
                    }
                }
            });
        }
    },

    clickSearchDrop:function() {
        $('.clickSearchDrop').click(function () {
            var parent = $(this).parents('.panel-info');
            if(parent.hasClass('act')){
                $('.clickSearchDrop').text('Mở rộng')
            }
            else{
                $('.clickSearchDrop').text('Thu gọn')
            }
            parent.toggleClass('act');
        });
    },
    dateTimePicker:function(){
        var dateToday = new Date();
        $('.date').datetimepicker({
            timepicker:false,
            format:'d-m-Y',
            lang:'vi',
        });
    },
    btnResetData:function(_element, _title){
        $(_element).click(function(){
            var _href = $(this).attr('href');
            jConfirm(_title, 'Xác nhận', function(r) {
                if(r){
                    window.location.href = _href;
                    return true;
                }
            });
            return false;
        });
    },
    tooltip:function(){
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })
    },
    copyText:function(){
        var copyText = new ClipboardJS('.copyText');
        copyText.on('success', function (e) {
            console.log(e);
        });
        copyText.on('error', function (e) {
            console.log(e);
        });
    },
    clickAddAttime:function(){
        $('.click-add-attime').unbind().click(function(){
            var item = '<div class="row itemAttime"><div class="col-md-5"> <span class="lb">Ext Link</span> <input name="attime[]" autocomplete="off" class="attime form-control input-sm"></div><div class="col-md-5"> <span class="lb">Tiêu đề</span> <input name="word[]" autocomplete="off" class="word form-control input-sm"></div><div class="col-md-2"> <span class="del-attime" title="Xóa">X</span></div></div>';
            $('.arrAttime').append(item);
            ADMIN.clickRemoveAttime();
        });
    },
    clickRemoveAttime:function(){
        $('.itemAttime .del-attime').unbind().click(function(){
            var _this = $(this);
            jConfirm('Bạn muốn xóa [OK]:Đồng ý [Cancel]:Bỏ qua?)', 'Xác nhận', function(r) {
                if(r){
                    var parent = _this.parents('.itemAttime');
                    parent.remove();
                }
            });
        });
    },
}
