var UploadAdmin = {
    // Upload Multiple
    uploadMultipleImages: function(type) {
        jQuery('#sys_PopupUploadImgOtherPro').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();
        var urlAjaxUpload = BASE_URL+'ajax/upload?act=upload_image';
        var id_hiden = document.getElementById('id_hiden').value;
        var _token = $('input[name="_token"]').val();
        var settings = {
            url: urlAjaxUpload,
            method: "POST",
            allowedTypes:"jpg,png,jpeg",
            fileName: "multipleFile",
            formData: {id: id_hiden, type: type, _token: _token},
            multiple: (id_hiden==0)? false: true,
            onSubmit:function(){
                jQuery( "#sys_show_button_upload").hide();
                jQuery("#status").html("<span class='green'>Đang upload...</span>");
            },
            onSuccess:function(files,xhr,data){
                dataResult = JSON.parse(xhr);
                if(dataResult.intIsOK === 1){
                    //Gan lai id item cho id hiden: dung cho them moi, sua item
                    jQuery('#id_hiden').val(dataResult.id_item);
                    jQuery( "#sys_show_button_upload").show();

                    //Add
                    var checked_img_pro = "<div class='clear'></div><input type='radio' id='checked_image_"+dataResult.info.id_key+"' name='checked_image' value='"+dataResult.info.id_key+"' onclick='UploadAdmin.checkedImage(\""+dataResult.info.name_img+"\",\"" + dataResult.info.id_key + "\")'><label for='checked_image_"+dataResult.info.id_key+"' style='font-weight:normal'>Ảnh đại diện</label><br/>";

                    var delete_img = "<a href='javascript:void(0);' id='sys_delete_img_other_" + dataResult.info.id_key + "' onclick='UploadAdmin.removeImage(\""+dataResult.info.id_key+"\",\""+dataResult.id_item+"\",\""+dataResult.info.name_img+"\",\""+type+"\")' >Xóa ảnh</a>";
                    var html= "<li id='sys_div_img_other_" + dataResult.info.id_key + "'>";
                    html += "<div class='div_img_upload' >";
                    html += "<img height='80' src='" + dataResult.info.src + "'/>";
                    html += "<input type='hidden' id='sys_img_other_" + dataResult.info.id_key + "' class='sys_img_other' name='img_other[]' value='" + dataResult.info.name_img + "'/>";
                    html += checked_img_pro;
                    html += delete_img;
                    html +="</div></li>";
                    jQuery('#sys_drag_sort').append(html);

                    jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
                    UploadAdmin.getInsertImageContent(type, 'off');

                    //Sucsess
                    jQuery("#status").html("<span class='green'>Upload is success</span>");
                    setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",2000 );
                    setTimeout( "jQuery('#status').hide();",2000 );
                    setTimeout( "jQuery('#sys_PopupUploadImgOtherPro').modal('hide');",2500 );

                }
            },
            onError: function(files,status,errMsg){
                jQuery("#status").html("<span class='red'>Upload is Failed</span>");
            }
        }
        jQuery("#sys_mulitplefileuploader").uploadFile(settings);
    },

    // Upload One
    uploadBannerAdvanced: function(type) {
        jQuery('#sys_PopupUploadImgOtherPro').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();
        var urlAjaxUpload = BASE_URL+'ajax/upload?act=upload_image';
        var id_hiden = document.getElementById('id_hiden').value;
        var _token = $('input[name="_token"]').val();
        var settings = {
            url: urlAjaxUpload,
            method: "POST",
            allowedTypes:"jpg,png,jpeg,gif",
            fileName: "multipleFile",
            formData: {id: id_hiden, type: type, _token: _token},
            multiple: false,
            onSubmit:function(){
                jQuery( "#sys_show_button_upload").hide();
                jQuery("#status").html("<span class='green'>Đang upload...</span>");
            },
            onSuccess:function(files,xhr,data){
                dataResult = JSON.parse(xhr);
                if(dataResult.intIsOK === 1){
                    //gan lai id item cho id hiden: dung cho them moi, sua item
                    jQuery('#id_hiden').val(dataResult.id_item);
                    jQuery( "#sys_show_button_upload").show();

                    //show ảnh
                    var html = "<img width='300' src='" + dataResult.info.src + "'/>";
                    jQuery('#banner_image').val(dataResult.info.name_img);
                    jQuery('#sys_show_image_banner').html(html);

                    var img_new = dataResult.info.name_img;
                    if(img_new != ''){
                        jQuery("#img").attr('value', img_new);
                    }
                    //thanh cong
                    jQuery("#status").html("<span class='green'>Upload is success</span>");
                    setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",2000 );
                    setTimeout( "jQuery('#status').hide();",2000 );
                    setTimeout( "jQuery('#sys_PopupUploadImgOtherPro').modal('hide');",2500 );
                }
            },
            onError: function(files,status,errMsg){
                jQuery("#status").html("<span class='red'>Upload is Failed</span>");
            }
        }
        jQuery("#sys_mulitplefileuploader").uploadFile(settings);
    },

    checkedImage: function(nameImage,key){
        if (confirm('Bạn có muốn chọn ảnh này làm ảnh đại diện?')) {
            jQuery('#image_primary').val(nameImage);
            jQuery('#sys_delete_img_other_'+key).hide();

            //luu lai key anh chinh
            var key_pri = document.getElementById('sys_key_image_primary').value;
            jQuery('#sys_delete_img_other_'+key_pri).show();
            jQuery('#sys_key_image_primary').val(key);

        }
    },

    checkedImageHover: function(nameImage,key){
        jQuery('#image_primary_hover').val(nameImage);
    },

    removeImage: function(key,id,nameImage,type){

        if(jQuery("#image_primary_hover").length ){
            var img_hover = jQuery("#image_primary_hover").val();
            if(img_hover == nameImage){
                jQuery("#image_primary_hover").val('');
            }
        }

        if (confirm('Bạn có chắc xóa ảnh này?')) {
            var urlAjaxUpload = BASE_URL+'ajax/upload?act=remove_image';
            var _token = $('input[name="_token"]').val();
            jQuery.ajax({
                type: "POST",
                url: urlAjaxUpload,
                data: {id : id, nameImage : nameImage, type: type, _token: _token},
                responseType: 'json',
                success: function(data) {
                    dataResult = JSON.parse(data);
                    if(dataResult.intIsOK === 1){
                        jQuery('#sys_div_img_other_'+key).hide();
                        jQuery('#sys_img_other_'+key).val('');
                        jQuery('#sys_new_img_'+key).hide();
                    }else{
                        jQuery('#sys_msg_return').html(data.msg);
                    }
                }
            });
        }
        jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
        UploadAdmin.getInsertImageContent(type, 'off');
    },

    getInsertImageContent: function(type, popup) {
        if(popup == 'open'){
            jQuery('#sys_PopupImgOtherInsertContent').modal('show');
        }
        var urlAjaxUpload = BASE_URL+'ajax/upload?act=get_image_insert_content';
        var id_hiden = document.getElementById('id_hiden').value;
        var _token = $('input[name="_token"]').val();
        jQuery.ajax({
            type: "POST",
            url: urlAjaxUpload,
            data: "id_hiden=" + encodeURI(id_hiden) + "&type=" + encodeURI(type) + "&_token=" + encodeURI(_token),
            success: function(data){
                dataResult = JSON.parse(data);
                if(dataResult.intIsOK === 1){
                    var imagePopup = '';
                    for(var i = 0; i < dataResult['item'].length; i++) {
                        imagePopup += "<span class='float_left image_insert_content'>";
                        var insert_img = "<a class='img_item' href='javascript:void(0);' onclick='insertImgContent(\""+dataResult['item'][i]['large']+"\")' >";
                        imagePopup += insert_img;
                        imagePopup += "<img height=80 src='" + dataResult['item'][i]['small'] + "'/> </a>";
                        imagePopup += "</span>";
                    }
                    jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
                    jQuery('#sys_PopupImgOtherInsertContent #div_image').append(imagePopup);
                }
            }
        });
    },
    getInsertExtLinkContent:function(type, popup){
        if(popup == 'open'){
            jQuery('#sys_PopupImgOtherInsertExtLinkContent').modal('show');
        }
    }
};
