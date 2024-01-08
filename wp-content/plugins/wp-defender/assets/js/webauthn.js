!function(e){var n={};function t(a){if(n[a])return n[a].exports;var r=n[a]={i:a,l:!1,exports:{}};return e[a].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=e,t.c=n,t.d=function(e,n,a){t.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:a})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,n){if(1&n&&(e=t(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(t.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var r in e)t.d(a,r,function(n){return e[n]}.bind(null,r));return a},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="/",t(t.s=276)}({276:function(e,n,t){e.exports=t(277)},277:function(e,n){function t(e,n){var t="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!t){if(Array.isArray(e)||(t=function(e,n){if(!e)return;if("string"==typeof e)return a(e,n);var t=Object.prototype.toString.call(e).slice(8,-1);"Object"===t&&e.constructor&&(t=e.constructor.name);if("Map"===t||"Set"===t)return Array.from(e);if("Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t))return a(e,n)}(e))||n&&e&&"number"==typeof e.length){t&&(e=t);var r=0,i=function(){};return{s:i,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(e){throw e},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var d,s=!0,o=!1;return{s:function(){t=t.call(e)},n:function(){var e=t.next();return s=e.done,e},e:function(e){o=!0,d=e},f:function(){try{s||null==t.return||t.return()}finally{if(o)throw d}}}}function a(e,n){(null==n||n>e.length)&&(n=e.length);for(var t=0,a=new Array(n);t<n;t++)a[t]=e[t];return a}!function(e){var n=!1,a=e(".defender-biometric-wrap"),r=a.find(".register-authenticator-box"),i=a.find("button"),d=a.find("#authenticator-identifier"),s=a.find("input[name='authenticator-type']"),o=a.find(".process-desc"),l=a.find(".process-auth-desc"),c=a.find(".records"),f=a.find(".no-record");function u(){var n=e(".auth-methods-table #row-webauthn");n.find(".wpdef-ui-toggle").is(":checked")&&void 0!==webauthn.user_handle_match_failed.show_notice&&!0===webauthn.user_handle_match_failed.show_notice&&1===n.find(".user-handle-mismatch").length?n.find(".user-handle-mismatch").show():n.find(".user-handle-mismatch").hide()}function h(e,n){var t=arguments.length>2&&void 0!==arguments[2]&&arguments[2],a=arguments.length>3&&void 0!==arguments[3]&&arguments[3],r="";!0===t?r='<span class="loading"></span>':"success"===e?r='<span class="dashicons dashicons-yes-alt"></span>':"error"===e&&(r='<span class="dashicons dashicons-warning"></span>');var i=a?'<button type="button" class="notice-dismiss"></button>':"";return'<div class="notice notice-'+e+'"><p>'+r+'<span class="message">'+n+"</span>"+i+"</p></div>"}function p(e){var n=void 0!==webauthn.user_handle_match_failed.authenticators&&Array.isArray(webauthn.user_handle_match_failed.authenticators)&&webauthn.user_handle_match_failed.authenticators.includes(wpdefBase64Url2Base64(atob(e.key)));return'<tr data-key="'+e.key+'" data-label="'+e.label+'"><td><span class="wpdef-field-label">'+e.label+'</span><input type="text" class="wpdef-field-rename-identifier regular-text" value="'+e.label+'" style="display:none;"/><div class="field-error" style="display:none;">'+wp.i18n.__("Add an authenticator identifier.","wpdef")+'</div><button type="button" class="toggle-row-content"></button></td><td><span class="col-name">'+wp.i18n.__("Type","wpdef")+"</span>"+("platform"===e.auth_type?wp.i18n.__("Platform","wpdef"):wp.i18n.__("Roaming","wpdef"))+'</td><td><span class="col-name">'+wp.i18n.__("Date Registered","wpdef")+"</span>"+e.added+'</td><td><span class="rename-control"><span class="wpdef-rename-btn">'+wp.i18n.__("Rename","wpdef")+'</span><span class="button button-primary wpdef-rename-update-btn" style="display:none;"><span class="label-btn">'+wp.i18n.__("Save","wpdef")+'</span><span class="loading" style="display:none;"></span></span><span class="wpdef-tbl-sep sep-rename" style="display:none;">|</span><span class="wpdef-rename-cancel-btn" style="display:none;">'+wp.i18n.__("Cancel","wpdef")+'</span></span><span class="wpdef-tbl-sep sep-delete">|</span><span class="wpdef-delete-btn">'+wp.i18n.__("Delete","wpdef")+'</span></td></tr><tr class="wpdef-user-handle-notice '+(n?"active":"")+'"><td colspan="4"><div class="wpdef-notice warning"><p><span class="dashicons dashicons-warning"></span><span class="wpdef-notice-message">'+webauthn.i18n.auth_user_handle_mismatch_notice+"</span></p></div></td></tr>"}function b(){n=!0,i.attr("disabled","disabled"),d.attr("disabled","disabled"),s.attr("disabled","disabled")}function w(){n=!1,i.removeAttr("disabled"),d.removeAttr("disabled").val(""),s.removeAttr("disabled","disabled")}function m(){a.find("#defender-biometric-tbl .wpdef-rename-update-btn").hide(),a.find("#defender-biometric-tbl .wpdef-rename-cancel-btn").hide(),a.find(".sep-rename").hide(),a.find("#defender-biometric-tbl .loading").hide(),a.find("#defender-biometric-tbl .wpdef-field-rename-identifier").hide().removeClass("required"),a.find("#defender-biometric-tbl .field-error").hide(),a.find("#defender-biometric-tbl .wpdef-rename-btn").show(),a.find("#defender-biometric-tbl .wpdef-field-label").show(),a.find(".sep-delete").show(),a.find(".wpdef-delete-btn").show()}!function(e){if(Array.isArray(e)){var n="";e.forEach((function(e,t){n+=p(e)})),""!==n?(f.hide(),c.show().html(n)):(f.show(),c.hide())}}(webauthn.registered_auths),u(),e("body").on("click",".defender-biometric-wrap .notice-dismiss",(function(n){n.preventDefault(),e(this).closest(".notice").remove()})),e("body").on("click",".defender-biometric-wrap .wpdef-new-btn",(function(e){e.preventDefault(),r.show(),m()})),e("body").on("click",".register-authenticator-box #wpdef-register-authenticator-close-btn",(function(e){e.preventDefault(),!0!==n&&(r.hide(),r.find("input.required").removeClass("required"),r.find(".field-error").hide(),o.html(""))})),e("body").on("click",".register-authenticator-box #wpdef-register-authenticator-btn",(function(t){if(t.preventDefault(),!0!==n){var a=s.filter(":checked").val(),i=e.trim(d.val());if(r.find("input.required").removeClass("required"),r.find(".field-error").hide(),!a||!i)return a||(s.addClass("required"),s.closest("tr").find(".field-error").show()),void(i||(d.addClass("required"),d.siblings(".field-error").show()));b(),o.html(h("info",webauthn.i18n.registration_start,!0)),e.ajax({url:webauthn.admin_url,type:"GET",data:{action:"defender_webauthn_create_challenge",type:a,_def_nonce:webauthn.nonce},success:function(n){if(!1===n.success||void 0===n.data.challenge)return o.html(h("error",webauthn.i18n.authenticator_reg_failed,!1,!0)),void w();var t=n.data,r=Uint8Array.from(window.atob(wpdefBase64Url2Base64(t.challenge)),(function(e){return e.charCodeAt(0)})),d=Uint8Array.from(window.atob(wpdefBase64Url2Base64(t.user.id)),(function(e){return e.charCodeAt(0)})),s={challenge:r,rp:{id:t.rp.id,name:t.rp.name},user:{id:d,name:t.user.name,displayName:t.user.displayName},pubKeyCredParams:t.pubKeyCredParams,authenticatorSelection:t.authenticatorSelection,timeout:t.timeout};t.excludeCredentials&&(s.excludeCredentials=t.excludeCredentials.map((function(e){return e.id=Uint8Array.from(window.atob(wpdefBase64Url2Base64(e.id)),(function(e){return e.charCodeAt(0)})),e})));var l=t.clientID;delete t.clientID,navigator.credentials.create({publicKey:s}).then((function(e){return{id:e.id,type:e.type,rawId:wpdefArrayToBase64String(new Uint8Array(e.rawId)),response:{clientDataJSON:wpdefArrayToBase64String(new Uint8Array(e.response.clientDataJSON)),attestationObject:wpdefArrayToBase64String(new Uint8Array(e.response.attestationObject))}}})).then(JSON.stringify).then((function(n){e.ajax({url:webauthn.admin_url,type:"POST",data:{action:"defender_webauthn_verify_challenge",data:window.btoa(n),name:i,usernameless:"false",client_id:l,type:a,_def_nonce:webauthn.nonce},success:function(e){!0===e.success?(f.hide(),c.show().append(p(e.data)),o.html(h("success",webauthn.i18n.authenticator_reg_success,!1,!0))):o.html(h("error",webauthn.i18n.authenticator_reg_failed,!1,!0)),w()},error:function(){o.html(h("error",webauthn.i18n.authenticator_reg_failed,!1,!0)),w()}})})).catch((function(e){var n=webauthn.i18n.authenticator_reg_failed;void 0!==e.message&&-1!==e.message.indexOf("already registered")&&(n=webauthn.i18n.multiple_reg_attempt),o.html(h("error",n,!1,!0)),w()}))},error:function(){o.html(h("error",webauthn.i18n.authenticator_reg_failed,!1,!0)),w()}})}})),e("body").on("click","#defender-biometric-tbl .wpdef-delete-btn",(function(t){if(!0!==n){var a=e(t.currentTarget),r=a.closest("tr"),i=a.html(),d=r.attr("data-key");!0===confirm(webauthn.i18n.remove_auth)&&(b(),a.html('<span class="loading"></span>'),e.ajax({url:webauthn.admin_url,type:"POST",data:{action:"defender_webauthn_remove_authenticator",key:d,_def_nonce:webauthn.nonce},success:function(e){!0===e.success?(r.next(".wpdef-user-handle-notice").remove(),r.remove(),0===c.find("tr").length&&(c.hide(),f.show())):(a.html(i),alert(e.data)),w()}}))}})),e("body").on("click","#defender-biometric-tbl .wpdef-rename-btn",(function(t){if(!0!==n){var a=e(t.currentTarget),r=a.closest("tr"),i=r.attr("data-label");m(),a.hide().siblings(".wpdef-rename-update-btn,.wpdef-rename-cancel-btn").show(),r.find(".wpdef-field-label").hide(),r.find(".sep-delete").hide(),r.find(".wpdef-delete-btn").hide(),r.find(".wpdef-field-rename-identifier").val(i).show().focus(),r.find(".sep-rename").show()}})),e("body").on("click","#defender-biometric-tbl .wpdef-rename-cancel-btn",(function(t){if(!0!==n){var a=e(t.currentTarget),r=a.closest("tr"),i=r.attr("data-label");a.hide().siblings(".wpdef-rename-update-btn").hide(),r.find(".sep-rename").hide(),r.find(".wpdef-field-rename-identifier").hide().val(i),r.find(".field-error").hide(),a.siblings(".wpdef-rename-btn").show(),r.find(".wpdef-field-label").show(),r.find(".sep-delete").show(),r.find(".wpdef-delete-btn").show()}})),e("body").on("click","#defender-biometric-tbl .wpdef-rename-update-btn",(function(t){if(!0!==n){var a=e(t.currentTarget),r=a.closest("tr"),i=r.find(".wpdef-field-rename-identifier"),d=r.attr("data-key"),s=e.trim(i.val());if(!s)return i.addClass("required"),void i.siblings(".field-error").show();i.removeClass("required"),i.siblings(".field-error").hide(),b(),a.find(".label-btn").html(wp.i18n.__("Saving","wpdef")),r.find(".loading").show(),e.ajax({url:webauthn.admin_url,type:"POST",data:{action:"defender_webauthn_rename_authenticator",key:d,label:s,_def_nonce:webauthn.nonce},success:function(e){!0===e.success?(r.attr("data-label",s),i.siblings(".wpdef-field-label").html(s)):alert(e.data),w(),a.hide().find(".label-btn").html(wp.i18n.__("Save","wpdef")),a.find(".loading").hide(),a.siblings(".wpdef-rename-cancel-btn").hide(),a.siblings(".sep-rename").hide(),i.hide(),i.siblings(".wpdef-field-label").show(),a.siblings(".wpdef-rename-btn").show(),r.find(".wpdef-delete-btn").show(),r.find(".sep-delete").show()}})}})),e("body").on("click",".defender-biometric-wrap .wpdef-verify-btn",(function(r){r.preventDefault(),!0!==n&&(b(),l.html(h("info",webauthn.i18n.authentication_start,!0)),e.ajax({url:webauthn.admin_url,type:"POST",data:{action:"defender_webauthn_get_option",username:webauthn.username,_def_nonce:webauthn.nonce},success:function(n){if(!1===n.success||void 0===n.data.challenge){var r="";return r="undefined"!==n.data.message&&"undefined"!==n.data.code&&0<n.data.code?n.data.message:webauthn.i18n.authenticator_verification_failed,l.html(h("error",r,!1,!0)),void w()}var i=n.data;if(i.challenge=Uint8Array.from(window.atob(wpdefBase64Url2Base64(i.challenge)),(function(e){return e.charCodeAt(0)})),i.allowCredentials){var d,s=t(i.allowCredentials);try{for(s.s();!(d=s.n()).done;){var o=d.value;o.id=Uint8Array.from(window.atob(wpdefBase64Url2Base64(o.id)),(function(e){return e.charCodeAt(0)}))}}catch(e){s.e(e)}finally{s.f()}}var c=i.clientID;delete i.clientID,navigator.credentials.get({publicKey:i}).then((function(e){return{id:e.id,type:e.type,rawId:wpdefArrayToBase64String(new Uint8Array(e.rawId)),response:{authenticatorData:wpdefArrayToBase64String(new Uint8Array(e.response.authenticatorData)),clientDataJSON:wpdefArrayToBase64String(new Uint8Array(e.response.clientDataJSON)),signature:wpdefArrayToBase64String(new Uint8Array(e.response.signature)),userHandle:e.response.userHandle?wpdefArrayToBase64String(new Uint8Array(e.response.userHandle)):null}}})).then(JSON.stringify).then((function(n){e.ajax({url:webauthn.admin_url,type:"POST",data:{action:"defender_webauthn_verify_response",data:window.btoa(n),client_id:c,username:webauthn.username,_def_nonce:webauthn.nonce},success:function(e){var n="";!0===e.success?n=h("success",webauthn.i18n.authenticator_verification_success,!1,!0):void 0!==e.data.message&&"Invalid user handle"===e.data.message?(notice_message=wp.i18n.sprintf(webauthn.i18n.authenticator_verification_failed_user_handle_mismatch,e.data.label),n=h("error",notice_message,!1,!0),a.find("#defender-biometric-tbl .records [data-key="+e.data.key+"]").next(".wpdef-user-handle-notice").show()):n=h("error",webauthn.i18n.authenticator_verification_failed,!1,!0),l.html(n),w()},error:function(){l.html(h("error",webauthn.i18n.authenticator_verification_failed,!1,!0)),w()}})})).catch((function(e){l.html(h("error",webauthn.i18n.authenticator_verification_failed,!1,!0)),w()}))},error:function(){l.html(h("error",webauthn.i18n.authenticator_verification_failed,!1,!0)),w()}}))})),e("body").on("click","#defender-biometric-tbl .toggle-row-content",(function(n){e(n.currentTarget).closest("tr").toggleClass("expanded")})),e("body").on("click",".auth-methods-table #row-webauthn .user-handle-mismatch.is-dismissible",(function(n){e(n.currentTarget).remove(),e.ajax({url:webauthn.admin_url,type:"POST",data:{action:"defender_webauthn_disable_user_handle_match_failed_notice",_def_nonce:webauthn.nonce},success:function(e){return!0}})})),e("body").on("click",".auth-methods-table #row-webauthn .wpdef-ui-toggle",u)}(jQuery)}});