var MemberAction = {};

MemberAction.login = function(formId) {
    if (MemberAction.isSubmit != true) {
        var result = FwValidator.inspection(formId);
        var form = EC$('#' + formId);

        if (result.passed == true) {
            var bResult = false;

            var sIsAdult = EC$('#is_adult_certification').val();
            if (sIsAdult == 'T'){
                EC$('<input id="returnUrl_adult" name="returnUrl_adult" value="adult_certification" type="hidden" />').insertAfter("#returnUrl");
            }

            try {
                setLoginKey(form);
            } catch(e) {}


            if (mobileWeb != true) {
                EC$.ajax({
                    type: 'POST',
                    url:  '/exec/front/Member/CheckCaptcha/',
                    dataType: 'json',
                    async: false,
                    success: function(response){
                        bResult = true;
                        try {
                            if (response['captcha_layer'] == true) {
                                // 로그인 버튼 클릭 후, 한번더 로그인을 클릭 했을 경우, 기존의 layer 삭제
                                if (EC$("#captcha_iframe").length > 0) {
                                    EC$("#captcha_iframe").hide();
                                }
                                MemberAction.layerpopup();
                            } else {
                                form.submit();
                                MemberAction.isSubmit = true;
                            }
                        } catch(e) {}
                    }
                });
            }

            if (bResult == false) {
                form.submit();
                MemberAction.isSubmit = true;
            }
        }
    }
    return false;
};

MemberAction.layerpopup = function() {
    if (mobileWeb == true) {

        var body_height = EC$('body').height();
        var source = '<div id="captcha_popup" style="position:absolute; top:0; left:0; width:100%;height:' + body_height + 'px; background:#000000; z-index:999;">' +
                     '<iframe src="/protected/captcha_login.html" style="width:100%; height:' + body_height + 'px; border:0;"></iframe></div>';

        EC$('body').append(source);

    } else {
        var oElement = document.createElement('div');

        oElement.id = 'captcha_popup';
        oElement.style.position = 'absolute';
        oElement.style.top      = Math.max(0, ((EC$(window).height() - 550) / 2) + EC$(window).scrollTop()) + 'px';
        oElement.style.left     = Math.max(0, ((EC$(window).width() - 450) / 2) + EC$(window).scrollLeft()) + 'px';
        oElement.style.zIndex   = '99';

        oElement.innerHTML = '<iframe id="captcha_iframe" src="/protected/captcha_login.html" scrolling="no" frameborder="0" allowTransparency="true" style="width:450px;height:550px;"></iframe>';
        document.body.appendChild(oElement);
    }

};

MemberAction.CaptchaLogin = function() {
    var result = true;
    if (EC$("#member_id").val() == '') {
        alert(__('아이디를 입력해 주세요'));
        EC$("#member_id").focus();
        result = false;
        return false;
    }

    if (EC$("#member_passwd").val() == '') {
        alert(__('비밀번호를 입력해 주세요'));
        EC$("#member_passwd").focus();
        result = false;
        return false;
    }

    if (EC$("#captcha_text").val() == '' || EC$("#captcha_text").val() == '자동입력 방지문자') {
        alert(__('자동입력 방지문자를 입력해 주세요'));
        EC$("#captcha_text").val('');
        EC$("#captcha_text").focus();
        result = false;
        return false;
    }

    if (result == true) {
        var sIsAdult = EC$(window.parent.document).find("#is_adult_certification").val();
        if (sIsAdult == 'T'){
            EC$('<input id="returnUrl_adult" name="returnUrl_adult" value="adult_certification" type="hidden" />').insertAfter("#returnUrl");
        }

        EC$('<input type="hidden" id="this_position" name="this_position" value="layer" />').insertAfter("#returnUrl");       // parent.replace 하기 위해
        EC$('#returnUrl').val(EC$(window.parent.document).find("#returnUrl").val());                                            // 로그인 성공 후 페이지
        EC$('#forbidIpUrl').val(EC$(window.parent.document).find("#forbidIpUrl").val());
        EC$('#certificationUrl').val(EC$(window.parent.document).find("#certificationUrl").val());

        this.submit();

        var doc = window.parent.document;
        var ifr = doc.getElementById('captcha_iframe');
        ifr.parentNode.removeChild(ifr);
    }

    return false;
};

MemberAction.CaptchaClose = function() {
    EC$(window.parent.document.body).find('#captcha_iframe').hide();
};

MemberAction.logout = function(formId) {
    EC$('#' + formId).submit();
};

MemberAction.FormActionDynamic = function(actionPath) {
    EC$.post(actionPath, function(response) {

        if (response.result == true) {

            alert(__('탈퇴 성공'));
            history.back();

        } else {

            alert(__('탈퇴 실패'));

        }

    });
};

//로그인상태유지 체크박스 confirm
MemberAction.confirmLoginKeeping = function(targetThis) {
    if (targetThis.checked == false) {
        return false;
    }
    if (!confirm(__('로그인 상태를 유지하시겠습니까?\n타인이 개인정보를 도용할 수 있으니,\n주의하시기 바랍니다.'))) {
        targetThis.checked = false;
    }
};

//연동로그인은 몰 > 서비스로그인 > 몰 이런 리다이렉트가 생기다보니, 쿠키로 세팅하여 로그인유지 확인함
MemberAction.setLoginKeepFlag = function(sUseFlag) {
    var date = new Date();
    var sCookieName = 'use_login_keep_' + EC_SDE_SHOP_NUM;
    date.setTime(date.getTime() + (1 * 60 * 1000));
    document.cookie = sCookieName + "=" + sUseFlag + "; path=/; expires=" + date.toGMTString() + ";";
};

MemberAction.setLoginKeepBind = function() {
    if (EC$('input[name="use_login_keeping"]').is(':checked') === true) {
        MemberAction.setLoginKeepFlag('T');
    } else {
        MemberAction.setLoginKeepFlag('F');
    }
};

MemberAction.plusAppRequestSet = function () {
    var $useLoginKeep = EC$('input[name="use_login_keeping"]');
    $useLoginKeep.prop('checked', true);
    $useLoginKeep.change(function(){ MemberAction.setLoginKeepBind(); });
};

//플러스앱에서 SNS로그인 요청을할때
MemberAction.plusAppSnsLogin = function () {
    var sSnsLoginType = EC$.url().param('sns_login_request') || '';
    var oXansMemberLoginTags = EC$('.xans-member-login').find('a');

    if (sSnsLoginType === 'facebook') {
        var sFacebookFuncRegex = /FBLogin(N)?(\((\'member_form_[0-9]+\')?\);)/;
        var oFacebookFuncCheckInterval = setInterval(function(){
            if (typeof FB !== 'undefined') {
                EC$.each(oXansMemberLoginTags, function(){
                    var sOuterHtml = EC$(this)[0].outerHTML;
                    if (sFacebookFuncRegex.test(sOuterHtml) === true) {
                        EC$(this)[0].onclick.call();
                        return false;
                    }
                });
                clearInterval(oFacebookFuncCheckInterval);
            }
        }, 1000);
    }
};

// PathRole 가져오기
MemberAction.getPathRole = function () {
    var sPathRole;

    if (SHOP.getProductVer() === 1) {
        var aPathRoleMap = {
            '/member/login.html': 'MEMBER_LOGIN'
        };

        sPathRole = aPathRoleMap[document.location.pathname];
    } else {
        sPathRole = EC$( 'meta[name="path_role"]' ).attr( 'content' );
    }

    return sPathRole;
};

// 쿠키 굽기
MemberAction.setCookie = function (cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = 'expires='+ d.toUTCString();
    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
};

// 플러스앱접근인지 확인
var isPlusAppAccess = function(){
    if (navigator.userAgent.indexOf('Cafe24Plus') !== -1 && (mobileWeb === true || top.mobileWeb === true)) {
        return true;
    }
    return false;
};


// SNS 로그인 웹페이지에서 분기 처리
MemberAction.snsLogin = function (sSnsName, sReturnUrl) {
    window.name = 'winParent';
    // 플러스앱전용
    if (isPlusAppAccess() === true) {

        if (sSnsName == 'googleplus') {

            var sLoginUrl = location.protocol + '//' + location.hostname;
            sLoginUrl +=    '/Api/Member/Oauth2CustomtabLogin/' + sSnsName + '/?resolver=custom_tab&returnUrl='+sReturnUrl;

            location.href = 'login://oauth_web?provider_code='+sSnsName+'$&login_url='+sLoginUrl;
        } else {
            location.href='/api/member/Oauth2ClientLogin/'+sSnsName+'/?returnUrl='+sReturnUrl;
        }
    } else {
        window.open('/api/member/Oauth2ClientLogin/'+sSnsName+'/', 'winSnsLogin', 'toolbar=no location=no scrollbars=yes resizable=yes width=472 height=539');
    }
    return false;
};

// 카카오싱크 로그인
MemberAction.kakaosyncLogin = function (clientSecret) {
    setKakaoSdkInit(clientSecret);
    Kakao.Auth.authorize({
        redirectUri: location.origin + '/Api/Member/Oauth2ClientCallback/kakao/'
    });
};

// 카카오싱크 로그인 커스텀 (디자인가이드 적용 함수)
MemberAction.kakaosyncLoginCustom = function (clientSecret) {
    setKakaoSdkInit(clientSecret);
    Kakao.Auth.authorize({
        redirectUri: location.origin + '/Api/Member/Oauth2ClientCallback/kakao/',
        state: 'kakaosync_design_guide_custom=' + document.location.href
    });
};

EC$(function(){
    //return URL 쿠키 굽기
    var sPathRole = MemberAction.getPathRole();

    if (sPathRole === 'MEMBER_LOGIN') {
        var sReturnUrl;

        EC$.url().param('returnUrl') === undefined ? sReturnUrl = '/' : sReturnUrl = EC$.url().param('returnUrl');
        MemberAction.setCookie('return_url',sReturnUrl,1);
    }

    // 플러스앱전용
    if (isPlusAppAccess() === true) {
        MemberAction.plusAppRequestSet();
        MemberAction.plusAppSnsLogin();
    }

    //enter key
    EC$("input[name='member_id'], input[name='member_passwd']").each(function(){
        EC$(this).on('keyup', function(e){
            if (EC$(this).parents('form').attr('id') == 'joinForm') return;
            if (e.which == '13') {
                var sFormId = EC$(this).parents('form').attr('id');
                MemberAction.login(sFormId);
            }
        });
    });

    // 모바일일경우 input 박스에 가이드해주기
    try {
        if (window.location.hostname.substr(0, 2) == 'm.' || window.location.hostname.substr(0, 12) == 'mobile--shop' || window.location.hostname.substr(0, 11) == 'skin-mobile') {
            EC$('#order_name').attr('placeholder', __('주문자명'));
            EC$('#order_name').attr('title', __('주문자명'));

            EC$('#order_id').attr('placeholder', __('NUMBER.HYPHEN.INCLUDED', 'APP.MEMBER.RESOURCE.JS'));
            EC$('#order_id').attr('title', __('NUMBER.HYPHEN.INCLUDED', 'APP.MEMBER.RESOURCE.JS'));
            // EC$('#ssn').attr('placeholder', '주민번호(\'-\'제외)');

            EC$('#order_password').attr('placeholder', __('비회원 주문 패스워드'));
            EC$('#order_password').attr('title', __('비회원 주문 패스워드'));
        } else {
            EC$('#order_id').attr('placeholder', __('HYPHENINCLUDED', 'APP.MEMBER.RESOURCE.JS'));
            EC$('#order_id').attr('title', __('HYPHENINCLUDED', 'APP.MEMBER.RESOURCE.JS'));
        }
    } catch (e) {

    }

    // 알리페이지 로그인
    EC$("#alipayLogin").click(function () {
        var returnUrl = EC$('#returnUrl').val();
        var url = '/exec/front/member/alipaylogin?returnUrl='+returnUrl;
        var option = 'menubar=no, toolbar=no, location=no, status=no, scrollbars=yes, resizable=yes';
        window.open(url,"_blank", option);
    });

});

/**
 * 바로구매하기/장바구니담기 Action  - 로그인하지 않았을 경우
 */
function check_action_nologin()
{
    alert(__('CAN.PURCHASE.GROUP', 'GLOBAL.BUY.LIMIT'));
    return false;
}


/**
 * 암호화 시간 input 설정
 */
function setLoginKey(sFormId)
{

    EC$.ajax({
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        url: '/exec/front/Member/loginKey',
        success: function (oRes) {
            if (oRes.sIsPass != 'T') {
                return;
            }

            //var sMemberId = sFormId.find("#member_id").val();
            // 암호화 된 값을 받을 input_hidden 생성
            EC$("<input>").attr({
                type: "hidden",
                name: "sLoginKey",
                id: "sLoginKey",
                value: oRes.sKey
            }).appendTo(sFormId);
        }
    });
}

/**
 * KAKAO JS SDK init
 */
function setKakaoSdkInit(clientSecret)
{
    // Kakao 객체가 이미 초기화 되어있다면 cleanup해주고 다시 init 해준다
    // Kakao 객체가 중복으로 init되면 함수 실행시 에러 발생하는 현상 방지
    if (Kakao.isInitialized()) {
        Kakao.cleanup();
    }
    Kakao.init(clientSecret);
}
// 앱에서 로그인유지 체크하려는데, 같은 js가 계속 호출되는 문제로 해당파일추가
EC$(function() {
    // 플러스앱전용
    if (isPlusAppAccess() === true) {
        MemberAction.setLoginKeepBind();
    }
});
var memberVerifyMobile = {};

/**
 * 회원 가입시 인증 번호 전송에 필요한 정보 암호화
 */
memberVerifyMobile.joinSendVerificationNumber = function()
{
    AuthSSLManager.weave({
        'auth_mode': 'encrypt',
        'aEleId': ['joinForm::mobile1', 'joinForm::mobile2', 'joinForm::mobile3'],
        'auth_callbackName': 'memberVerifyMobile.sendVerificationNumberEncryptResult'
    });
};

/**
 * 회원 정보 수정시 인증 번호 전송에 필요한 정보 암호화
 */
memberVerifyMobile.editSendVerificationNumber = function()
{
    AuthSSLManager.weave({
        'auth_mode': 'encrypt',
        'aEleId': ['editForm::mobile1', 'editForm::mobile2', 'editForm::mobile3'],
        'auth_callbackName': 'memberVerifyMobile.sendVerificationNumberEncryptResult'
    });
};

memberVerifyMobile.sendVerificationNumberEncryptResult = function(output)
{
    var sEncrypted = encodeURIComponent(output);

    if (AuthSSLManager.isError(sEncrypted) == true) {
        return;
    }

    EC$.ajax({
        url: '/exec/front/member/ApiAuthsms',
        cache: false,
        type: 'POST',
        dataType: 'json',
        data: '&encrypted_str=' + sEncrypted,
        success: function(response) {
            try {
                response.sResultMsg = decodeURIComponent(response.sResultMsg);
            } catch (e) {}

            if (response.sResultCode == '0000') {
                memberVerifyMobile.sendVerificationNumberSuccess(response);
            } else {
                memberVerifyMobile.sendVerificationNumberFail(response);
            }
        }
    });
};

/**
 * 회원 가입 휴대전화 인증 번호 전송 성공
 */
memberVerifyMobile.sendVerificationNumberSuccess = function(response)
{
    // sms 실제 발송 여부 확인
    if (response.isSendMobileSms !== true) {
        return;
    }

    if (EC$("#btn_action_verify_mobile").html() == __('GET.VERIFICATION.NUMBER', 'MEMBER.UTIL.VERIFY')) {
        alert(__('NUMBER.RESENT', 'MEMBER.UTIL.VERIFY'));
    }

    this.verifyNumberCountdown();

    EC$("#btn_action_verify_mobile").html(__('RETRANSMISSION', 'MEMBER.UTIL.VERIFY'));
    EC$("#confirm_verify_mobile").removeClass("displaynone");
    EC$("#confirm_verify_mobile").show();

    var oVerifySmsNumber = EC$("#verify_sms_number");

    oVerifySmsNumber.attr("placeholder", "");

    if (EC_MOBILE === true) {
        alert(response.sResultMsg);
        return;
    }

    EC$("#result_send_verify_mobile_fail").hide();
    EC$("#result_send_verify_mobile_success").removeClass("displaynone");
    EC$("#result_send_verify_mobile_success").show();
    EC$("#btn_verify_mobile_confirm").prop("disabled", false);
    oVerifySmsNumber.prop('disabled', false);
};

/**
 * 회원 가입 휴대전화 인증 번호 전송 실패
 */
memberVerifyMobile.sendVerificationNumberFail = function(response)
{
    EC$("#btn_action_verify_mobile").html(__('GET.VERIFICATION.NUMBER', 'MEMBER.UTIL.VERIFY'));

    if (EC_MOBILE === true) {
        alert(response.sResultMsg);
        return;
    }

    EC$("#result_send_verify_mobile_success").hide();

    var oResultSendVerifyMobileFail = EC$("#result_send_verify_mobile_fail");
    oResultSendVerifyMobileFail.removeClass("displaynone");
    oResultSendVerifyMobileFail.show();
    oResultSendVerifyMobileFail.html(response.sResultMsg.replace(/\n/g, "<br />"));

    EC$("#expiryTime").html("");
    clearInterval(memberVerifyMobile.timer);

    var oVerifySmsNumber = EC$("#verify_sms_number");
    if (response.sResultCode == "3001") {
        EC$("#confirm_verify_mobile").removeClass("displaynone");
        EC$("#confirm_verify_mobile").show();
        oVerifySmsNumber.val("");
        oVerifySmsNumber.attr("placeholder", __('TRY.AGAIN.IN.MINUTES', 'MEMBER.UTIL.VERIFY'));
        oVerifySmsNumber.prop("disabled", true);
        EC$("#btn_verify_mobile_confirm").prop("disabled", true);
        return;
    } else {
        oVerifySmsNumber.removeAttr("placeholder");
        EC$("#expiryTime").prop("disabled", false);
    }

    EC$("#confirm_verify_mobile").hide();
};

/**
 * 휴대폰 인증 번호 카운트 다운
 */
memberVerifyMobile.verifyNumberCountdown = function()
{
    //초기값
    var iMinute = 3;
    var iSecond = "00";
    var iZero = "";
    var iTmpSecond = "";

    // 초기화
    if (typeof memberVerifyMobile.timer != "undefined") {
        clearInterval(memberVerifyMobile.timer);
    }

    EC$("#expiryTime").html(iMinute +":"+iSecond);

    // 초기화
    memberVerifyMobile.timer = setInterval(function () {
        iSecond = iSecond.toString();

        if (iSecond.length < 2) {
            iTmpSecond = iSecond;
            iZero = 0;
            iSecond = iZero + iTmpSecond;
        }

        // 설정
        EC$("#expiryTime").html(iMinute +":"+iSecond);
        if (iSecond == 0 && iMinute == 0) {
            clearInterval(memberVerifyMobile.timer); /* 타이머 종료 */
            EC$("#confirm_verify_mobile").hide();
            EC$("#result_send_verify_mobile_success").hide();
        } else {
            iSecond--;
            // 분처리
            if(iSecond < 0){
                iMinute--;
                iSecond = 59;
            }
        }
    }, 1000); /* millisecond 단위의 인터벌 */
};


/**
 * 회원 가입시 인증 번호 전송에 필요한 정보 암호화
 */
memberVerifyMobile.joinVerifySmsNumberConfirm = function()
{
    AuthSSLManager.weave({
        'auth_mode': 'encrypt',
        'aEleId': ['joinForm::mobile1', 'joinForm::mobile2', 'joinForm::mobile3', 'joinForm::verify_sms_number'],
        'auth_callbackName': 'memberVerifyMobile.joinVerifySmsNumberConfirmResult'
    });
};

/**
 * 회원 가입시 인증 번호 전송에 필요한 정보 암호화
 */
memberVerifyMobile.editVerifySmsNumberConfirm = function()
{
    AuthSSLManager.weave({
        'auth_mode': 'encrypt',
        'aEleId': ['editForm::mobile1', 'editForm::mobile2', 'editForm::mobile3', 'editForm::verify_sms_number'],
        'auth_callbackName': 'memberVerifyMobile.joinVerifySmsNumberConfirmResult'
    });
};

/**
 * 인증번호 확인
 * @param output
 */
memberVerifyMobile.joinVerifySmsNumberConfirmResult = function(output)
{
    var sEncrypted = encodeURIComponent(output);

    if (AuthSSLManager.isError(sEncrypted) == true) {
        return;
    }

    EC$.ajax({
        url: '/exec/front/member/ApiAuthJoinconfirm',
        cache: false,
        type: 'POST',
        dataType: 'json',
        data: '&encrypted_str=' + sEncrypted,
        success: function(response) {
            try {
                response['sResultMsg'] = decodeURIComponent(response['sResultMsg']);
            } catch (err) {}

            if (response.sResultCode == '0000') {
                memberVerifyMobile.joinVerifySmsNumberConfirmSuccess(response);
            } else {
                memberVerifyMobile.joinVerifySmsNumberConfirmFail(response);
            }
        }
    });
};

/**
 * 회원 가입 휴대전화 인증 성공
 */
memberVerifyMobile.joinVerifySmsNumberConfirmSuccess = function(response)
{
    EC$("#verify_sms_number").val("");
    EC$("#verify_sms_number").attr("placeholder", response['sResultMsg']);
    EC$("#verify_sms_number").prop('disabled', true);

    EC$("#expiryTime").hide();
    EC$("#isMobileVerify").val("T");
};

/**
 * 회원 가입 휴대전화 인증 실패
 */
memberVerifyMobile.joinVerifySmsNumberConfirmFail = function(response)
{
    alert(response['sResultMsg']);
    EC$("#isMobileVerify").val("F");
};

memberVerifyMobile.requireMobileNumber = function(oMobileElement)
{
    if (oMobileElement.val() != "") {
        return;
    }

    var oResultSendVerifyMobileFail = EC$("#result_send_verify_mobile_fail");
    oResultSendVerifyMobileFail.removeClass("displaynone");
    oResultSendVerifyMobileFail.show();
    oResultSendVerifyMobileFail.html(__('ENTER.YOUR.MOBILE.NUMBER', 'MEMBER.UTIL.VERIFY'));
};

/**
 * 휴대전화 인증 여부
 * @returns {boolean}
 */
memberVerifyMobile.isMobileVerify = function()
{
    if (EC$("#use_checking_mobile_number_duplication").val() != "T") {
        return true;
    }

    if (EC$("#isMobileVerify").val() == "F") {
        return false;
    }

    return true;
};

/**
 * 휴대전화 변경 전 기존 정보 확인
 */
memberVerifyMobile.setEditMobileNumberBefore = function(aMember)
{
    if (aMember.hasOwnProperty('mobile1')) {
        memberVerifyMobile.editMobile1 = aMember['mobile1'];
    }

    if (aMember.hasOwnProperty('mobile2')) {
        memberVerifyMobile.editMobile2 = aMember['mobile2'];
    }

    if (aMember.hasOwnProperty('mobile3')) {
        memberVerifyMobile.editMobile3 = aMember['mobile3'];
    }
};

/**
 * 휴대전화 번호 변경 됐는지 확인
 */
memberVerifyMobile.isMobileNumberChange = function()
{
    if (EC$("#use_checking_mobile_number_duplication").val() != "T") {
        return false;
    }

    if (EC$("#mobile1").val() != memberVerifyMobile.editMobile1) {
        return true;
    }

    if (EC$("#mobile2").val() != memberVerifyMobile.editMobile2) {
        return true;
    }

    if (EC$("#mobile3").val() != memberVerifyMobile.editMobile3) {
        return true;
    }

    return false;
};

EC$(function() {
    EC$("#mobile2").on('blur', function(){
        memberVerifyMobile.requireMobileNumber(EC$(this));
    });

    EC$("#mobile3").on('blur', function(){
        memberVerifyMobile.requireMobileNumber(EC$(this));
    });
});
var memberSns = {
    sFormId : '',
    oUserInfo : [],
    sProvider : '',
    oAuth2EncData : [],
    sProviderTitle : '',
    accessToken : ''
};
memberSns.snsAgreementLayer = function (sProviderTitle)
{
    EC$('#sns_name').html(sProviderTitle);

    // 부모창에 중복으로 있으면 안되는 내용에 대해서 0 초과 엘리먼트들을 제거해준다
    EC$(parent.top.document).contents().find('div[id=mCafe24SnsAgree]').slice(1).remove();

    setTimeout(function () {
        memberSns.resizeSnsLayer();
    }, 500);

    // SNS로 가입시 연락처 기본값을 국가코드를 기반으로 설정해준다
    var aTelInfo = [];
    aTelInfo['ja_JP'] = '81';
    aTelInfo['en_US'] = '1';
    aTelInfo['es_ES'] = '34';
    aTelInfo['pt_PT'] = '351';
    aTelInfo['zh_CN'] = '86';
    aTelInfo['zh_TW'] = '886';
    aTelInfo['ko_KR'] = '82';
    var sTelCode = aTelInfo[SHOP.getLanguage()];
    EC$('#mCafe24SnsAgree select[id=phone1] option[value='+sTelCode+']').prop('selected', true);
    EC$('#mCafe24SnsAgree select[id=mobile1] option[value='+sTelCode+']').prop('selected', true);

    EC$('#sns_name').html(sProviderTitle);

    if (mobileWeb == true) {
        EC$(top.document.body).addClass('eMobilePopup');
    }

    EC$('#agreementClick').click(function(){
        // 입력값 체크용으로만 join 진행
        EC$('#is_use_checking_join_info').val('T');
        // SNS용으로 셋팅후 회원가입시 필요 js체크 실행
        memberJoinAction();
    });
};

// sns 연동후 회원가입 Layer 노출시 sns 연동으로 가져온 user 기본정보를 되도록 layer에 부려준다
memberSns.snsDefaultUserInfo = function (userInfo)
{
    var oJson = JSON.parse((userInfo));
    userInfo = oJson.aUserInfo;

    // 페이스북 처럼 이메일 값만 넘어오는 경우 email1,2로 나누어 담아준다
    var aEmail = [];
    if (userInfo.email) {
        aEmail = userInfo.email.split('@');
        userInfo.email1 = aEmail[0];
        userInfo.email2 = aEmail[1];
    }

    if (userInfo.name) {
        var sSnsName = userInfo.name.replace(/\+/g, " ");

        if (EC$("#sns_join input[name=name]").length > 0) {
            EC$("#sns_join input[name=name]").val(sSnsName);
        } else {
            if (EC$('#snsNameContents') != undefined) {
                EC$('#snsNameContents').html(sSnsName);
            }
        }
    }

    if (userInfo.email1) EC$("#sns_join input[name=email1]").val(userInfo.email1);
    if (userInfo.email2) EC$("#sns_join input[name=email2]").val(userInfo.email2);
    if (userInfo.email2) EC$("#sns_join #email3 option[value=etc]").prop('selected', true);
    // 이메일 입력란이 1개만 있는경우 (해외몰)
    if (!EC$("#sns_join input[name=email2]").attr('name') && EC$("#sns_join input[name=email1]").attr('name')) {
        if (userInfo.email1 && userInfo.email2) EC$("#sns_join input[name=email1]").val(userInfo.email1 + '@' + userInfo.email2);
    }

    if ($("#sns_join input[name=email1]").attr('name') && $("#sns_join input[name=email1]").val().length > 0) {
        // 국내몰일 경우 이메일 중복 체크 기능을 사용하는 경우에만 호출.
        if ( SHOP.getLanguage() == 'ko_KR' ) {
            if ( $("#useCheckEmailDuplication").val() == "T" || $('#is_email_auth_use').val() == 'T') { setDuplEmail(); }
        }
        // 해외 몰일경우 그냥 호출.
        else {
            setDuplEmail();
        }
    }

};
/**
 * iframe 페이지 로딩이 느릴 경우 resize 제대로 안되는 문제 ==> iframe onload 시 호출
 * 레이어를 화면 중앙에 위치 하도록
 * (2014.07.21 by wcchoi)
 */
memberSns.resizeSnsLayer = function()
{
    var height = EC$(parent.ifrm_sns.document).contents().find('#mCafe24SnsAgree').height();
    var width = EC$(parent.ifrm_sns.document).contents().find('#mCafe24SnsAgree').width();

    if (height > 0 && width > 0) {
        EC$(parent.top.document).contents().find('#ifrm_sns').height(height + 2);
        EC$(parent.top.document).contents().find('#ifrm_sns').width(width);
    }

};

// sns 가입 정보 취함
memberSns.joinProc = function()
{
    var aOauth2EncDataArray = [];

    aOauth2EncDataArray = JSON.parse(JSON.stringify(top.memberSns.oUserInfo));
    memberSns.joinProcStep2(aOauth2EncDataArray);
};

// sns 가입,로그인 액션 form 암호화 발송 처리
memberSns.joinProcStep2 = function(aOauth2EncDataArray)
{
    // 공통 input 값들에 대해 배열 변수에 담아준다
    var sOauth2EncDataArray = snsOauth2EncDataSet(aOauth2EncDataArray);

    EC$("input[name='sOauth2EncData']").val(encodeURIComponent(sOauth2EncDataArray));
    AgreementLayer.EncryptSubmit();

};


/**
 * sns AgreementLayer 에서 네이버,페이스북 공통 처리되는 기능 펑션
 */
function openAgreementLayer(sProviderCode, oAuth2EncData, sProviderTitle)
{
    if (oAuth2EncData == '') {
        return;
    }

    oAuth2EncData = decodeURIComponent(oAuth2EncData);

    top.memberSns.sProvider = sProviderCode;
    top.memberSns.oAuth2EncData = oAuth2EncData;
    top.memberSns.sProviderTitle = sProviderTitle;

    AuthSSLManager.weave({
        'auth_mode': 'decryptClient',
        'auth_string': oAuth2EncData,
        'auth_callbackName': 'openAgreementLayerStep2'
    });
}

try {
    var provider = $(parent.top.document).contents().find("input[name='provider']").val();
    var encrypt = $(parent.top.document).contents().find("input[name='encrypt']").val();

    if (provider != '' && encrypt != '') {
        // localStorage set
        localStorage.setItem('provider', provider);
        localStorage.setItem('encrypt', encrypt);
    }
    // ECHOSTING-163478 대응 webview에서 window.open()이 새창이 아닌 페이지이동으로 동작할때
    if (top.memberSns && top.memberSns.oAuth2EncData) {
        openAgreementLayer(top.memberSns.sProvider, top.memberSns.oAuth2EncData, top.memberSns.sProviderTitle);
    }
} catch (e) {}

function openAgreementLayerStep2(sOutput)
{
    var sOutput = decodeURIComponent(sOutput);
    if ( AuthSSLManager.isError(sOutput) == true ) {
        return;
    }
    var userInfo = AuthSSLManager.unserialize(sOutput);

    // 한글 경우 rawurlencode 하여 한번더 decodeURIComponent 처리를 해준다
    userInfo.sEncryptInfo = decodeURIComponent(userInfo.sEncryptInfo);

    // sns 유저 기본 정보 노출
    top.memberSns.oUserInfo = userInfo;

    // sns 를 통한 로그인 시도시 처리
    if (EC$('#mCafe24SnsAgree') && EC$('#joinForm')) {
        // sns AgreementLayer 에서 네이버,페이스북 공통 처리되는 기능 펑션
        memberSns.snsAgreementLayer(top.memberSns.sProviderTitle);
        memberSns.snsDefaultUserInfo(userInfo.sEncryptInfo);
    } else {
        EC$('#agreementClick').click(function(){
            AgreementLayer.EncryptSubmit();
        });
    }
    EC$(parent.top.document).contents().find("input[name='sOauth2EncData']").val(top.memberSns.oAuth2EncData);
    EC$(parent.top.document).contents().find('#mCafe24SnsAgree').css('display', 'block');
    EC$('#mCafe24SnsAgree').css('display', 'block');

    // 사용완료 변수 초기화
    top.memberSns.oAuth2EncData = [];
}

function snsOauth2EncDataSet(aOauth2EncDataArray)
{
    aOauth2EncDataArray = JSON.parse(aOauth2EncDataArray['sEncryptInfo']);

    if (EC$("#terms_information")) {
        var terms_information = (EC$("#terms_information").prop("checked")) ? '1' : '0';
        aOauth2EncDataArray['aUserInfo']['fb_terms_information'] = terms_information;
    }
    if (EC$("#terms_consignment")) {
        var terms_consignment = (EC$("#terms_consignment").prop("checked")) ? '1' : '0';
        aOauth2EncDataArray['aUserInfo']['fb_terms_consignment'] = terms_consignment;
    }

    // Naver등 FaceBook 이외 셋팅
    var terms_information = (EC$("#terms_information").prop("checked")) ? 'T' : 'F';
    var terms_consignment = (EC$("#terms_consignment").prop("checked")) ? 'T' : 'F';
    aOauth2EncDataArray['aUserInfo']['terms_information'] = terms_information;
    aOauth2EncDataArray['aUserInfo']['terms_consignment'] = terms_consignment;

    // 공통 INPUT 셋팅
    EC$("#sns_join,#form_agreement").find("input[type=text]").each(function(){
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('name')] = EC$(this).val();
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('id')] = EC$(this).val();
    });
    EC$("#sns_join,#form_agreement").find("select").each(function(){
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('name')] = EC$(this).val();
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('id')] = EC$(this).val();
    });
    EC$("#sns_join,#form_agreement").find("input[type=radio]:checked").each(function(){
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('name')] = EC$(this).val();
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('id')] = EC$(this).val();
    });
    EC$("#sns_join,#form_agreement").find("input[type=checkbox]").each(function(){
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('name')] = (EC$(this).prop('checked')) ? 'T' : 'F';
        aOauth2EncDataArray['aUserInfo'][EC$(this).attr('id')] = (EC$(this).prop('checked')) ? 'T' : 'F';
    });

    return JSON.stringify(aOauth2EncDataArray);
}
/**
 * appMemberUtilDomLoginUrl::assign => js로 변환
 */
var MemberReturnUrl = {

    setReturnUrl: function()
    {
        var sLoginReferrer = '/';

        try{
            // 주소창에 returnUrl 있는 경우
            if (location.search.indexOf('returnUrl=') > 0) {

                var aTmpReferrer = location.search.split('returnUrl=');
                var aReferrer = aTmpReferrer[1].split('&');

                sLoginReferrer = decodeURIComponent(aReferrer[0]);
            } else {

                var sPathRole = this.getPathRole();
                var sCurrentUrl = location.pathname + location.search;

                // referrer가 현재 도메인인 경우에만 처리
                if (document.referrer.indexOf(location.hostname) > 0 || document.referrer == '') {

                    // 로그인 페이지에서 진입 한 경우는 document.referrer가 returnUrl
                    if (sPathRole == 'MEMBER_LOGIN') {

                        var sMain = location.protocol + '//' + location.hostname + '/';
                        sLoginReferrer = document.referrer;

                        if (sLoginReferrer == '') {
                            sLoginReferrer = sCurrentUrl;
                        }

                        // sLoginReferrer 에 login,join 등이 있을때는 메인으로
                        if ( this.getSearchString(sLoginReferrer) ) {
                            sLoginReferrer = sMain;
                        }

                    } else {

                        // 로그인 페이지가 이닌 좌측 로그인 폼에서 로그인 했을때
                        if ( !this.getSearchString(sCurrentUrl) ) {
                            sLoginReferrer = sCurrentUrl;
                        }
                    }
                }
            }

            EC$("form[action*='/exec/front/Member/login'] > input:hidden[name^='returnUrl']").each(function() {
                EC$(this).val(sLoginReferrer);
            });

        } catch(e) {}
    },

    /**
     * URL에 login,join가 있으면 true
     * @param sUrl
     * @returns {boolean}
     */
    getSearchString : function(sUrl)
    {
        var iSearchCnt = sUrl.search(new RegExp('/login|join|agreement|find_id|intro|find_passwd_info|passwd_reset|exec/', 'i'));
        if (iSearchCnt > 0) {
            return true;
        } else {
            return false;
        }
    },

    /**
     * path role 획득
     */
    getPathRole : function()
    {
        var sPathRole = '';

        // EUC-KR 에서 path role 획득
        if (SHOP.getProductVer() === 1) {

            // path 와 role 매핑
            var aPathRoleMap = {
                '/member/login.html': 'MEMBER_LOGIN'
            };

            // 페이지 경로로부터 path role 획득
            sPathRole = aPathRoleMap[document.location.pathname];
        } else {

            // UTF-8에서 path role 획득
            sPathRole = EC$('meta[name="path_role"]').attr('content');
        }
        return sPathRole;
    }
};

EC$(function() {
    MemberReturnUrl.setReturnUrl();
});
/**
 * FwValidator
 *
 * @package     jquery
 * @subpackage  validator
 */

var FwValidator = {

    /**
     * 디버그 모드
     */
    DEBUG_MODE : false,

    /**
     * 결과 코드
     */
    CODE_SUCCESS    : true,
    CODE_FAIL       : false,

    /**
     * 어트리뷰트 명
     */
    ATTR_FILTER     : 'fw-filter',
    ATTR_MSG        : 'fw-msg',
    ATTR_LABEL      : 'fw-label',
    ATTR_FIREON     : 'fw-fireon',
    ATTR_ALONE      : 'fw-alone',

    /**
     * 응답객체들
     */
    responses       : {},

    /**
     * 엘리먼트별 필수 입력 에러 메세지
     */
    requireMsgs     : {},

    /**
     * 엘리먼트의 특정 필터별 에러 메세지
     */
    elmFilterMsgs   : {},

    /**
     * jQuery 별칭 정의
     * EC$ 가 기본으로 로드되지 않는 환경에서 사용시에 대한 처리
     */
    jQuery          : window.EC$ || window.$,

    /**
     * Validator 기본 이벤트 등록
     */
    bind : function(formId, expand) {

        var self = this;
        var formInfo = this.Helper.getFormInfo(formId);

        if (formInfo === false) {
            alert('The form does not exist - bind');
            return false;
        }

        var elmForm = formInfo.instance;

        var Response = this._response(formId);

        this._fireon(formId, elmForm, Response);
        this._submit(formId, elmForm, expand);

        return true;

    },

    /**
     * Validator 검사 진행
     *
     * @param string formId
     * @return object | false
     */
    inspection : function(formId, expand) {

        expand = (expand === true) ? true : false;

        var self = this;
        var Response = this._response(formId);

        if (Response === false) {
            alert('The form does not exist - inspection');
            return false;
        }

        if (Response.elmsTarget.length == 0) {
            return this.Helper.getResult(Response, this.CODE_SUCCESS);
        }

        Response.elmsTarget.each(function(){
            self._execute(Response, this);
        });

        if (Response.elmsCurrErrorField.length > 0) {

            if (expand !== true) {
                this.Handler.errorHandler(Response.elmsCurrErrorField[0]);
            } else {
                this.Handler.errorHandlerByExapnd(Response);
            }

            return Response.elmsCurrErrorField[0];

        }

        return this.Helper.getResult(Response, this.CODE_SUCCESS);

    },

    /**
     * submit 이벤트 등록
     *
     * @param string    formId
     * @param object    elmForm
     */
    _submit : function(formId, elmForm, expand) {
        var self = this;
        var handler = function(event){
            var result = false;

            // 중복 요청 방지로 추가
            event.stopImmediatePropagation();

            try{
                result = self.inspection(formId, expand);
            }catch(e){
                alert(e);
                return false;
            }

            if(!result || result.passed === self.CODE_FAIL){
                return false;
            };

            var callback = self._beforeSubmit(elmForm);

            return callback !== false ? true : false;
        };

        elmForm.unbind('submit');
        elmForm.bind('submit', handler);

        // window.$에서 submit() 처리시 오동작으로 인해 추가 
        if (this.jQuery !== window.$) {
            $(elmForm).unbind('submit');
            $(elmForm).bind('submit', handler);
        }
    },

    /**
     * fireon 이벤트 등록
     *
     * @param string                formId
     * @param object                elmForm
     * @param FwValidator.Response  Response
     */
    _fireon : function(formId, elmForm, Response) {
        var $ = this.jQuery;
        var self = this;
        var formInfo = this.Helper.getFormInfo(formId);

        $(formInfo.selector).find('*['+this.ATTR_FILTER+']['+this.ATTR_FIREON+']').each(function(){
            var elm = $(this);
            var evtName = self.Helper.trim(elm.attr(self.ATTR_FIREON));
            var elmMsg = '';

            elm.unbind(evtName);
            elm.bind(evtName, function(){
                var result = self._execute(Response, this);
                var targetField = Response.elmCurrField;

                //에러 메세지가 출력되 있다면 일단 지우고 체킹을 시작한다.
                if(typeof elmMsg == 'object'){
                    elmMsg.remove();
                }

                if(result > -1){
                    elmMsg = self.Handler.errorHandlerByFireon(Response.elmsCurrErrorField[result]);
                }else{
                    self.Handler.successHandlerByFireon(self.Helper.getResult(Response, self.CODE_FAIL));
                }
            });
        });
    },

    /**
     * Response 객체 생성
     *
     * @param string formId
     * @return FwValidator.Response | false
     */
    _response : function(formId) {
        var $ = this.jQuery;
        var formInfo = this.Helper.getFormInfo(formId);

        if (formInfo === false) {
            alert('The form does not exist - find');
            return false;
        }

        var elmForm = formInfo.instance;
        var elmsTarget = $(formInfo.selector).find('*[' + this.ATTR_FILTER + ']');

        this.responses[formId] = new FwValidator.Response();

        this.responses[formId].formId = formId;
        this.responses[formId].elmForm = elmForm;
        this.responses[formId].elmsTarget = elmsTarget;

        return this.responses[formId];

    },

    /**
     * BeforeExecute 콜백함수 실행
     *
     * @param FwValidator.Response Response
     */
    _beforeExecute : function(Response) {

        var count = this.Handler.beforeExecute.length;

        if (count == 0) return;

        for (var i in this.Handler.beforeExecute) {
            this.Handler.beforeExecute[i].call(this, Response);
        }

    },

    /**
     * BeforeSubmit 콜백함수 실행
     *
     * @param object elmForm (jquery 셀렉터 문법으로 찾아낸 폼 객체)
     */
    _beforeSubmit : function(elmForm) {

        if(typeof this.Handler.beforeSubmit != 'function') return true;

        return this.Handler.beforeSubmit.call(this, elmForm);

    },

    /**
     * 엘리먼트별 유효성 검사 실행
     *
     * @param FwValidator.Response  Response
     * @param htmlElement           elmTarget
     * @return int(에러가 발생한 elmCurrField 의 인덱스값) | -1(성공)
     */
    _execute : function(Response, elmTarget) {
        var $ = this.jQuery;
        var RESULT_SUCCESS = -1;

        Response.elmCurrField = $(elmTarget);
        Response.elmCurrLabel = Response.elmCurrField.attr(this.ATTR_LABEL);
        Response.elmCurrFieldType = this.Helper.getElmType(Response.elmCurrField);
        Response.elmCurrFieldDisabled = elmTarget.disabled;
        Response.elmCurrValue = this.Helper.getValue(Response.formId, Response.elmCurrField);
        Response.elmCurrErrorMsg = Response.elmCurrField.attr(this.ATTR_MSG);

        //_beforeExecute 콜백함수 실행
        this._beforeExecute(Response);

        //필드가 disabled 일 경우는 체크하지 않음.
        if (Response.elmCurrFieldDisabled === true) {
            return RESULT_SUCCESS;
        }

        var filter = this.Helper.trim( Response.elmCurrField.attr(this.ATTR_FILTER) );

        if (filter == '') {
            return RESULT_SUCCESS;
        }

        //is로 시작하지 않는것들은 정규표현식으로 간주
        if (/^is/i.test(filter)) {
            var filters = filter.split('&');
            var count = filters.length;

            //필수항목이 아닌경우 빈값이 들어왔을경우는 유효성 체크를 통과시킴

            if ((/isFill/i.test(filter) === false) && !Response.elmCurrValue) {
                return RESULT_SUCCESS;
            }

            for (var i=0; i < count; ++i) {
                var filter = filters[i];
                var param = '';
                var filtersInfo = this.Helper.getFilterInfo(filter);

                filter = Response.elmCurrFilter = filtersInfo.id;
                param = filtersInfo.param;

                //필수 입력 필터의 경우 항목관리에서 사용자가 메세지를 직접 지정하는 부분이 있어 이렇게 처리
                if (filter == 'isFill') {
                    Response.elmCurrValue = this.Helper.trim(Response.elmCurrValue);
                    Response.elmCurrErrorMsg = this.requireMsgs[elmTarget.id] ? this.requireMsgs[elmTarget.id] : this.msgs['isFill'];
                } else {
                    var msg = Response.elmCurrField.attr(this.ATTR_MSG);

                    if (msg) {
                        Response.elmCurrErrorMsg = msg;
                    } else if (this.Helper.getElmFilterMsg(elmTarget.id, filter)) {
                        Response.elmCurrErrorMsg = this.Helper.getElmFilterMsg(elmTarget.id, filter);
                    } else {
                        Response.elmCurrErrorMsg = this.msgs[filter];
                    }

                }

                //존재하지 않는 필터인 경우 에러코드 반환
                if(this.Filter[filter] === undefined){
                    Response.elmCurrErrorMsg = this.msgs['notMethod'];
                    var result = this.Helper.getResult(Response, this.CODE_FAIL);

                    Response.elmsCurrErrorField.push(result);
                    return Response.elmsCurrErrorField.length - 1;
                }

                //필터 실행
                var result = this.Filter[filter](Response, param);

                if (result == undefined || result.passed === this.CODE_FAIL) {
                    Response.elmsCurrErrorField.push(result);

                    //Debug를 위해 넣어둔 코드(확장형 필터를 잘못 등록해서 return값이 없는 경우를 체크하기 위함)
                    if (result == undefined) {
                        alert('Extension Filter Return error - ' + filter);
                    }

                    return Response.elmsCurrErrorField.length - 1;
                }
            }
        } else {
            var msg = Response.elmCurrErrorMsg;
            Response.elmCurrErrorMsg = msg ? msg : this.msgs['isRegex'];
            var result = this.Filter.isRegex(Response, filter);

            if(result.passed === this.CODE_FAIL){
                Response.elmsCurrErrorField.push(result);

                return Response.elmsCurrErrorField.length - 1;
            }
        }

        return RESULT_SUCCESS;
    }
};

/**
 * FwValidator.Response
 *
 * @package     jquery
 * @subpackage  validator
 */

FwValidator.Response = function() {

    this.formId = null;
    this.elmForm = null;
    this.elmsTarget = null;
    this.elmsCurrErrorField = [];

    this.elmCurrField = null;
    this.elmCurrFieldType = null;
    this.elmCurrFieldDisabled = null;
    this.elmCurrLabel = null;
    this.elmCurrValue = null;
    this.elmCurrFilter = null;
    this.elmCurrErrorMsg = null;

    this.requireMsgs = {};

};

/**
 * FwValidator.Helper
 *
 * @package     jquery
 * @subpackage  validator
 */

FwValidator.Helper = {

    parent : FwValidator,

    /**
     * 메세지 엘리먼트의 아이디 prefix
     */
    msgIdPrefix : 'msg_',

    /**
     * 메세지 엘리먼트의 클래스 명 prefix
     */
    msgClassNamePrefix : 'msg_error_mark_',

    /**
     * 결과 반환
     */
    getResult : function(Response, code, param) {

        //특수 파라미터 정보(특정 필터에서만 사용함)
        param = param != undefined ? param : {};

        var msg = '';

        if (code === this.parent.CODE_FAIL) {

            try {
                msg = Response.elmCurrErrorMsg.replace(/\{label\}/i, Response.elmCurrLabel);
            } catch(e) {
                msg = 'No Message';
            }

        } else {

            msg = 'success';

        }

        var result = {};
        result.passed = code;
        result.formid = Response.formId;
        result.msg = msg;
        result.param = param;

        try {
        result.element = Response.elmCurrField;
        result.elmid = Response.elmCurrField.attr('id');
        result.filter = Response.elmCurrFilter;
        } catch(e) {}

        return result;

    },

    /**
     * 필터 정보 반환(필터이름, 파라미터)
     */
    getFilterInfo : function(filter) {
        var matches = filter.match(/(is[a-z]*)((?:\[.*?\])*)/i);

        return {
            id : matches[1],
            param : this.getFilterParams(matches[2])
        };
    },

    /**
     * 필터의 파라미터 스트링 파싱
     * isFill[a=1][b=1][c=1] 이런식의 멀티 파라미터가 지정되어 있는 경우는 배열로 반환함
     * isFill[a=1] 단일 파라미터는 파라미터로 지정된 스트링값만 반환함
     */
    getFilterParams : function(paramStr) {
        if (paramStr == undefined || paramStr == null || paramStr == '') {
            return '';
        }

        var matches = paramStr.match(/\[.*?\]/ig);

        if (matches == null) {
            return '';
        }

        var count = matches.length;
        var result = [];

        for (var i=0; i < count; i++) {
            var p = matches[i].match(/\[(.*?)\]/);
            result.push(p[1]);
        }

        if (result.length == 1) {
            return result[0];
        }

        return result;
    },

    /**
     * 필드 타입 반환(select, checkbox, radio, textbox)
     */
    getElmType : function(elmField) {
        var $ = this.parent.jQuery;

        elmField = $(elmField);

        var elTag = elmField[0].tagName;
        var result = null;

        switch (elTag) {
            case 'SELECT' :
                result = 'select';
                break;

            case 'INPUT' :
                if ($.fn.prop) {
                    var _type = elmField.prop('type').toLowerCase();
                } else {
                    var _type = elmField.attr('type').toLowerCase();
                }
                if(_type == 'checkbox') result = 'checkbox';
                else if(_type =='radio') result = 'radio';
                else result = 'textbox';

                break;

            case 'TEXTAREA' :
                result = 'textbox';
                break;

            default :
                result = 'textbox';
                break;
        }

        return result;
    },

    /**
     * 필드 값 반환
     */
    getValue : function(formId, elmField) {
        var $ = this.parent.jQuery;
        var result = '';
        var elmName = elmField.attr('name');
        var fieldType = this.getElmType(elmField);

        //checkbox 나 radio 박스는 value값을 반환하지 않음
        if (fieldType == 'checkbox' || fieldType == 'radio') {
            if(elmField.get(0).checked === true){
                result = elmField.val();
            }
            return result;
        }

        //alonefilter 속성이 Y 로 되어 있다면 해당 엘리먼트의 값만 반환함
        var aloneFilter = elmField.attr(this.parent.ATTR_ALONE);
        if(aloneFilter == 'Y' || aloneFilter == 'y'){
            return elmField.val();
        }

        //name이 배열형태로 되어 있다면 값을 모두 합쳐서 반환
        if( /\[.*?\]/.test(elmName) ){
            var formInfo = this.getFormInfo(formId);

            var groupElms = $(formInfo.selector +' [name="'+elmName+'"]');
            groupElms.each(function(i){
                var elm = $(this);
                result += elm.val();
            });
        }else{
            result = elmField.val();
        }

        return result;
    },

    /**
     * 에러메세지 엘리먼트 생성
     */
    createMsg : function(elm, msg, formId) {
        var $ = this.parent.jQuery;
        var elmMsg = document.createElement('span');

        elmMsg.id = this.msgIdPrefix + elm.attr('id');
        elmMsg.className = this.msgClassNamePrefix + formId;
        elmMsg.innerHTML = msg;

        return $(elmMsg);
    },

    /**
     * 에러메세지 엘리먼트 제거
     */
    removeMsg : function(elm) {
        var $ = this.parent.jQuery;
        var id = this.msgIdPrefix + elm.attr('id');
        var elmErr = $('#'+id);

        if (elmErr) elmErr.remove();
    },

    /**
     * 에러메세지 엘리먼트 모두 제거
     */
    removeAllMsg : function(formId) {
        var $ = this.parent.jQuery;
        var className = this.msgClassNamePrefix + formId;

        $('.' + className).remove();
    },

    /**
     * 문자열의 Byte 수 반환
     */
    getByte : function(str) {
        var encode = encodeURIComponent(str);
        var totalBytes = 0;
        var chr;
        var bytes;
        var code;

        for(var i = 0; i < encode.length; i++)
        {
            chr = encode.charAt(i);
            if(chr != "%") totalBytes++;
            else
            {
                code = parseInt(encode.substr(i+1,2),16);
                if(!(code & 0x80)) totalBytes++;
                else
                {
                    if((code & 0xE0) == 0xC0) bytes = 2;
                    else if((code & 0xF0) == 0xE0) bytes = 3;
                    else if((code & 0xF8) == 0xF0) bytes = 4;
                    else return -1;

                    i += 3 * (bytes - 1);

                    totalBytes += 2;
                }
                i += 2;
            }
        }

        return totalBytes;
    },

    /**
     * 지정한 엘리먼트의 필터 메세지가 존재하는가
     *
     * @param elmId (엘리먼트 아이디)
     * @param filter (필터명)
     * @return string | false
     */
    getElmFilterMsg : function(elmId, filter) {
        if (this.parent.elmFilterMsgs[elmId] == undefined) return false;
        if (this.parent.elmFilterMsgs[elmId][filter] == undefined) return false;

        return this.parent.elmFilterMsgs[elmId][filter];
    },

    /**
     * 폼 정보 반환
     *
     * @param formId (폼 아이디 혹은 네임)
     * @return array(
     *   'selector' => 셀렉터 문자,
     *   'instance' => 셀렉터 문법으로 검색해낸 폼 객체
     * ) | false
     */
    getFormInfo : function(formId) {
        var $ = this.parent.jQuery;
        var result = {};
        var selector = '#' + formId;
        var instance = $(selector);

        if (instance.length > 0) {
            result.selector = selector;
            result.instance = instance;

            return result;
        }

        selector = 'form[name="' + formId + '"]';
        instance = $(selector);

        if (instance.length > 0) {
            result.selector = selector;
            result.instance = instance;

            return result;
        }

        return false;
    },

    /**
     * 숫자형태의 문자열로 바꿔줌
     * 123,123,123
     * 123123,123
     * 123%
     * 123  %
     * 123.4
     * -123
     * ,123
     *
     * @param value
     * @return float
     */
    getNumberConv : function(value) {
        if (!value || value == undefined || value == null) return '';

        value = value + "";

        value = value.replace(/,/g, '');
        value = value.replace(/%/g, '');
        value = value.replace(/[\s]/g, '');

        if (this.parent.Verify.isFloat(value) === false) return '';

        return parseFloat(value);
    },

    /**
     * 문자열 앞 뒤 공백 제거
     *
     * @param string text
     * @return string
     */
    trim: function(text) {
        var trim = String.prototype.trim;

        return text == null ? "" : trim.call(text);
    }
};

/**
 * FwValidator.Handler
 *
 * @package     jquery
 * @subpackage  validator
 */

FwValidator.Handler = {

    parent : FwValidator,

    /**
     * 사용자 정의형 에러핸들러(엘리먼트 아이디별로 저장됨)
     */
    customErrorHandler : {},

    /**
     * 사용자 정의형 에러핸들러(필터별로 저장됨)
     */
    customErrorHandlerByFilter : {},

    /**
     * 사용자 정의형 성공핸들러(엘리먼트 아이디별로 저장됨)
     */
    customSuccessHandler : {},

    /**
     * 사용자 정의형 성공핸들러(필터별로 저장됨)
     */
    customSuccessHandlerByFilter : {},

    /**
     * FwValidator._execute에 의해 검사되기 전 실행되는 콜백함수
     */
    beforeExecute : [],

    /**
     * FwValidator._submit에서 바인딩한 onsubmit 이벤트 발생후 실행되는 콜백함수
     * {폼아이디 : 콜백함수, ...}
     */
    beforeSubmit : {},

    /**
     * 기본 메세지 전체를 오버라이딩
     */
    overrideMsgs : function(msgs) {
        if (typeof msgs != 'object') return;

        this.parent.msgs = msgs;
    },

    /**
     * 필드에 따른 필수 입력 에러메세지 설정
     */
    setRequireErrorMsg : function(field, msg) {
        this.parent.requireMsgs[field] = msg;
    },

    /**
     * 필터 타입에 따른 에러메세지 설정
     */
    setFilterErrorMsg : function(filter, msg) {
        this.parent.msgs[filter] = msg;
    },

    /**
     * 엘리먼트의 특정 필터에만 에러메세지를 설정
     */
    setFilterErrorMsgByElement : function(elmId, filter, msg) {
        if (this.parent.elmFilterMsgs[elmId] == undefined) {
            this.parent.elmFilterMsgs[elmId] = {};
        }

        this.parent.elmFilterMsgs[elmId][filter] = msg;
    },

    /**
     * 엘리먼트 아이디별 사용자정의형 에러핸들러 등록
     */
    setCustomErrorHandler : function(elmId, func) {
        if (typeof func != 'function') return;

        this.customErrorHandler[elmId] = func;
    },

    /**
     * 필터 타입별 사용자정의형 에러핸들러 등록
     */
    setCustomErrorHandlerByFilter : function(filter, func) {
        if (typeof func != 'function') return;

        this.customErrorHandlerByFilter[filter] = func;
    },

    /**
     * 엘리먼트 아이디별 사용자정의형 성공핸들러 등록
     */
    setCustomSuccessHandler : function(elmId, func) {
        if (typeof func != 'function') return;

        this.customSuccessHandler[elmId] = func;
    },

    /**
     * 필터 타입별 사용자정의형 성공핸들러 등록
     */
    setCustomSuccessHandlerByFilter : function(filter, func) {
        if (typeof func != 'function') return;

        this.customSuccessHandlerByFilter[filter] = func;
    },

    /**
     * 확장형 필터 등록
     */
    setExtensionFilter : function(filter, func) {
        if (typeof func != 'function') return;

        if (this.parent.Filter[filter] == undefined) {
            this.parent.Filter[filter] = func;
        }
    },

    /**
     * 각 엘리먼트가 FwValidator._execute에 의해 검사되기 전 실행되는 콜백함수 등록
     */
    setBeforeExecute : function(func) {
        if (typeof func != 'function') return;

        this.beforeExecute.push(func);
    },

    /**
     * FwValidator._submit 에서 바인딩된 onsubmit 이벤트의 콜백함수 등록(유효성 검사가 성공하면 호출됨)
     */
    setBeforeSubmit : function(func) {
        if (typeof func != 'function') return;

        this.beforeSubmit = func;
    },

    /**
     * 에러핸들러 - 기본
     */
    errorHandler : function(resultData) {
        if (this._callCustomErrorHandler(resultData) === true) return;

        alert(resultData.msg);
        resultData.element.focus();
    },

    /**
     * 에러핸들러 - 전체 펼침 모드
     */
    errorHandlerByExapnd : function(Response) {
        var count = Response.elmsCurrErrorField.length;

        //해당 폼에 출력된 에러메세지를 일단 모두 지운다.
        this.parent.Helper.removeAllMsg(Response.formId);

        for (var i=0; i < count; ++i) {
            var resultData = Response.elmsCurrErrorField[i];

            if (this._callCustomErrorHandler(resultData) === true) continue;

            var elmMsg = this.parent.Helper.createMsg(resultData.element, resultData.msg, resultData.formid).css({'color':'#FF3300'});
            elmMsg.appendTo(resultData.element.parent());
        }
    },

    /**
     * 에러핸들러 - fireon
     */
    errorHandlerByFireon : function(resultData) {
        if (this._callCustomErrorHandler(resultData) === true) return;

        //해당 항목의 에러메세지 엘리먼트가 있다면 먼저 삭제한다.
        this.parent.Helper.removeMsg(resultData.element);

        var elmMsg = this.parent.Helper.createMsg(resultData.element, resultData.msg, resultData.formid).css({'color':'#FF3300'});
        elmMsg.appendTo(resultData.element.parent());

        return elmMsg;
    },

    /**
     * 성공핸들러 - fireon
     */
    successHandlerByFireon : function(resultData) {

        this._callCustomSuccessHandler(resultData);

    },

    /**
     * 정의형 에러 핸들러 호출
     *
     * @return boolean (정의형 에러핸들러를 호출했을 경우 true 반환)
     */
    _callCustomErrorHandler : function(resultData) {
        //resultData 가 정의되어 있지 않은 경우
        if (resultData == undefined) {
            alert('errorHandler - resultData is not found');
            return true;
        }

        //해당 엘리먼트에 대한 Custom에러핸들러가 등록되어 있다면 탈출
        if (this.customErrorHandler[resultData.elmid] != undefined) {
            this.customErrorHandler[resultData.elmid].call(this.parent, resultData);
            return true;
        }

        //해당 필터에 대한 Custom에러핸들러가 등록되어 있다면 탈출
        if (this.customErrorHandlerByFilter[resultData.filter] != undefined) {
            this.customErrorHandlerByFilter[resultData.filter].call(this.parent, resultData);
            return true;
        }

        return false;
    },

    /**
     * 정의형 성공 핸들러 호출 - 기본적으로 fireon 속성이 적용된 엘리먼트에만 적용됨.
     */
    _callCustomSuccessHandler : function(resultData) {

        if (this.customSuccessHandler[resultData.elmid] != undefined) {
            this.customSuccessHandler[resultData.elmid].call(this.parent, resultData);
            return;
        }

        if (this.customSuccessHandlerByFilter[resultData.filter] != undefined) {
            this.customSuccessHandlerByFilter[resultData.filter].call(this.parent, resultData);
            return;
        }

    }
};

/**
 * FwValidator.Verify
 *
 * @package     jquery
 * @subpackage  validator
 */

FwValidator.Verify = {

    parent : FwValidator,

    isNumber : function(value, cond) {
        if (value == '') return true;

        if (!cond) {
            cond = 1;
        }

        cond = parseInt(cond);

        pos = 1;
        nga = 2;
        minpos = 4;
        minnga = 8;

        result = 0;

        if ((/^[0-9]+$/).test(value) === true) {
            result = pos;
        } else if ((/^[-][0-9]+$/).test(value) === true) {
            result = nga;
        } else if ((/^[0-9]+[.][0-9]+$/).test(value) === true) {
            result = minpos;
        } else if ((/^[-][0-9]+[.][0-9]+$/).test(value) === true) {
            result = minnga;
        }

        if (result & cond) {
            return true;
        }

        return false;
    },

    isFloat : function(value) {
        if (value == '') return true;

        return (/^[\-0-9]([0-9]+[\.]?)*$/).test(value);
    },

    isIdentity : function(value) {
        if (value == '') return true;

        return (/^[a-z]+[a-z0-9_]+$/i).test(value);
    },

    isKorean : function(value) {
        if (value == '') return true;

        var count = value.length;

        for(var i=0; i < count; ++i){
            var cCode = value.charCodeAt(i);

            //공백은 무시
            if(cCode == 0x20) continue;

            if(cCode < 0x80){
                return false;
            }
        }

        return true;
    },

    isAlpha : function(value) {
        if (value == '') return true;

        return (/^[a-z]+$/i).test(value);
    },

    isAlphaUpper : function(value) {
        if (value == '') return true;

        return (/^[A-Z]+$/).test(value);
    },

    isAlphaLower : function(value) {
        if (value == '') return true;

        return (/^[a-z]+$/).test(value);
    },

    isAlphaNum : function(value) {
        if (value == '') return true;

        return (/^[a-z0-9]+$/i).test(value);
    },

    isAlphaSpace : function(value) {
        if (value == '') return true;

        return (/^[a-zA-Z ]+$/).test(value);
    },

    isAlphaNumUpper : function(value) {
        if (value == '') return true;

        return (/^[A-Z0-9]+$/).test(value);
    },

    isAlphaNumLower : function(value) {
        if (value == '') return true;

        return (/^[a-z0-9]+$/).test(value);
    },

    isAlphaDash : function(value) {
        if (value == '') return true;

        return (/^[a-z0-9_-]+$/i).test(value);
    },

    isAlphaDashUpper : function(value) {
        if (value == '') return true;

        return (/^[A-Z0-9_-]+$/).test(value);
    },

    isAlphaDashLower : function(value) {
        if (value == '') return true;

        return (/^[a-z0-9_-]+$/).test(value);
    },

    isSsn : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        if ( (/[0-9]{2}[01]{1}[0-9]{1}[0123]{1}[0-9]{1}[1234]{1}[0-9]{6}$/).test(value) === false ) {
            return false;
        }

        var sum = 0;
        var last = value.charCodeAt(12) - 0x30;
        var bases = "234567892345";
        for (var i=0; i<12; i++) {
            sum += (value.charCodeAt(i) - 0x30) * (bases.charCodeAt(i) - 0x30);
        };
        var mod = sum % 11;

        if ( (11 - mod) % 10 != last ) {
            return false;
        }

        return true;
    },

    isForeignerNo : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        if ( (/[0-9]{2}[01]{1}[0-9]{1}[0123]{1}[0-9]{1}[5678]{1}[0-9]{1}[02468]{1}[0-9]{2}[6789]{1}[0-9]{1}$/).test(value) === false ) {
            return false;
        }

        var sum = 0;
        var last = value.charCodeAt(12) - 0x30;
        var bases = "234567892345";
        for (var i=0; i<12; i++) {
            sum += (value.charCodeAt(i) - 0x30) * (bases.charCodeAt(i) - 0x30);
        };
        var mod = sum % 11;
        if ( (11 - mod + 2) % 10 != last ) {
            return false;
        }

        return true;
    },

    isBizNo : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        if ( (/[0-9]{3}[0-9]{2}[0-9]{5}$/).test(value) === false ) {
            return false;
        }

        var sum = parseInt(value.charAt(0));
        var chkno = [0, 3, 7, 1, 3, 7, 1, 3];
        for (var i = 1; i < 8; i++) {
            sum += (parseInt(value.charAt(i)) * chkno[i]) % 10;
        }
        sum += Math.floor(parseInt(parseInt(value.charAt(8))) * 5 / 10);
        sum += (parseInt(value.charAt(8)) * 5) % 10 + parseInt(value.charAt(9));

        if (sum % 10 != 0) {
            return false;
        }

        return true;
    },

    isJuriNo : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        if ( (/^([0-9]{6})-?([0-9]{7})$/).test(value) === false ) {
            return false;
        }

        var sum = 0;
        var last = parseInt(value.charAt(12), 10);
        for (var i=0; i<12; i++) {
            if (i % 2 == 0) {  // * 1
                sum += parseInt(value.charAt(i), 10);
            } else {    // * 2
                sum += parseInt(value.charAt(i), 10) * 2;
            };
        };

        var mod = sum % 10;
        if( (10 - mod) % 10 != last ){
            return false;
        }

        return true;
    },

    isPhone : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        return (/^(02|0[0-9]{2,3})[1-9]{1}[0-9]{2,3}[0-9]{4}$/).test(value);
    },

    isMobile : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        return (/^01[016789][1-9]{1}[0-9]{2,3}[0-9]{4}$/).test(value);
    },

    isZipcode : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        return (/^[0-9]{3}[0-9]{3}$/).test(value);
    },

    isIp : function(value) {
        if (value == '') return true;

        return (/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){2,}$/).test(value);
    },

    isEmail : function(value) {
        value = this.parent.Helper.trim(value);
        if (value == '') return true;

        return (/^([a-z0-9\_\-\.]+)@([a-z0-9\_\-]+\.)+[a-z]{2,63}$/i).test(value);
    },

    isUrl : function(value) {
        if (value == '') return true;

        return (/http[s]?:\/\/[a-z0-9_\-]+(\.[a-z0-9_\-]+)+/i).test(value);
    },

    isDate : function(value) {
        value = value.replace(/-/g, '');
        if (value == '') return true;

        return (/^[12][0-9]{3}(([0]?[1-9])|([1][012]))[0-3]?[0-9]$/).test(value);
    },

    isPassport : function(value) {
        if (value == '') return true;

        //일반 여권
        if ( (/^[A-Z]{2}[0-9]{7}$/).test(value) === true ) {
            return true;
        }

        //전자 여권
        if ( (/^[A-Z]{1}[0-9]{8}$/).test(value) === true ) {
            return true;
        }

        return false;
    },

    isNumberMin : function(value, limit) {
        value = this.parent.Helper.getNumberConv(value);
        limit = this.parent.Helper.getNumberConv(limit);

        if (value < limit) {
            return false;
        }

        return true;
    },

    isNumberMax : function(value, limit) {
        value = this.parent.Helper.getNumberConv(value);
        limit = this.parent.Helper.getNumberConv(limit);

        if (value > limit) {
            return false;
        }

        return true;
    },

    isNumberRange : function(value, min, max) {
        value = this.parent.Helper.getNumberConv(value);

        min = this.parent.Helper.getNumberConv(min);
        max = this.parent.Helper.getNumberConv(max);

        if (value < min || value > max) {
            return false;
        }

        return true;
    }
};

/**
 * FwValidator.Filter
 *
 * @package     jquery
 * @subpackage  validator
 */

FwValidator.Filter = {

    parent : FwValidator,

    isFill : function(Response, cond) {
        if (typeof cond != 'string') {
            var count = cond.length;
            var result = this.parent.Helper.getResult(Response, parent.CODE_SUCCESS);

            for (var i = 0; i < count; ++i) {
                result = this._fillConditionCheck(Response, cond[i]);

                if (result.passed === true) {
                    return result;
                }
            }

            return result;
        }

        return this._fillConditionCheck(Response, cond);
    },

    isMatch : function(Response, sField) {
        var $ = this.parent.jQuery;

        if(Response.elmCurrValue == ''){
            return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
        }

        //Radio 나 Checkbox의 경우 무시
        if(Response.elmCurrFieldType == 'radio' || Response.elmCurrFieldType == 'checkbox'){
            return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
        }

        var elmTarget = $('#'+sField);
        var elmTargetValue = elmTarget.val();

        if (Response.elmCurrValue != elmTargetValue) {
            var label = elmTarget.attr(this.parent.ATTR_LABEL);
            var match = label ? label : sField;

            Response.elmCurrErrorMsg = Response.elmCurrErrorMsg.replace(/\{match\}/i, match);

            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isMax : function(Response, iLen) {
        var $ = this.parent.jQuery;
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        if (Response.elmCurrFieldType == 'radio' || Response.elmCurrFieldType == 'checkbox') {
            var chkCount = 0;
            var sName = Response.elmCurrField.attr('name');

            $('input[name="'+sName+'"]').each(function(i){
                if ($(this).get(0).checked === true) {
                    ++chkCount;
                }
            });

            if (chkCount > iLen) {
                result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            }

        } else {
            var len = Response.elmCurrValue.length;

            if (len > iLen) {
                result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            }
        }

        if (result.passed === this.parent.CODE_FAIL) {
            result.msg = result.msg.replace(/\{max\}/i, iLen);
        }

        return result;
    },

    isMin : function(Response, iLen) {
        var $ = this.parent.jQuery;
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        if(Response.elmCurrFieldType == 'radio' || Response.elmCurrFieldType == 'checkbox'){
            var chkCount = 0;
            var sName = Response.elmCurrField.attr('name');

            $('input[name="'+sName+'"]').each(function(i){
                if($(this).get(0).checked === true){
                    ++chkCount;
                }
            });

            if (chkCount < iLen) {
                result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            }

        }else{
            var len = Response.elmCurrValue.length;

            if(len < iLen){
                result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            }
        }

        if(result.passed === this.parent.CODE_FAIL){
            result.msg = result.msg.replace(/\{min\}/i, iLen);
        }

        return result;
    },

    isNumber : function(Response, iCond) {
        var result = this.parent.Verify.isNumber(Response.elmCurrValue, iCond);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isIdentity : function(Response){
        var result = this.parent.Verify.isIdentity(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isKorean : function(Response){
        var result = this.parent.Verify.isKorean(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlpha : function(Response){
        var result = this.parent.Verify.isAlpha(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaLower : function(Response){
        var result = this.parent.Verify.isAlphaLower(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },


    isAlphaSpace : function(Response){
        var result = this.parent.Verify.isAlphaSpace(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaUpper : function(Response){
        var result = this.parent.Verify.isAlphaUpper(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaNum : function(Response){
        var result = this.parent.Verify.isAlphaNum(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaNumLower : function(Response){
        var result = this.parent.Verify.isAlphaNumLower(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaNumUpper : function(Response){
        var result = this.parent.Verify.isAlphaNumUpper(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaDash : function(Response){
        var result = this.parent.Verify.isAlphaDash(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaDashLower : function(Response){
        var result = this.parent.Verify.isAlphaDashLower(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isAlphaDashUpper : function(Response){
        var result = this.parent.Verify.isAlphaDashUpper(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isSsn : function(Response){
        var result = this.parent.Verify.isSsn(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isForeignerNo : function(Response){
        var result = this.parent.Verify.isForeignerNo(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isBizNo : function(Response){
        var result = this.parent.Verify.isBizNo(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isJuriNo : function(Response){
        var result = this.parent.Verify.isJuriNo(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isPhone : function(Response){
        var result = this.parent.Verify.isPhone(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isMobile : function(Response){
        var result = this.parent.Verify.isMobile(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isZipcode : function(Response){
        var result = this.parent.Verify.isZipcode(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isIp : function(Response){
        var result = this.parent.Verify.isIp(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isEmail : function(Response){
        var result = this.parent.Verify.isEmail(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isUrl : function(Response){
        var result = this.parent.Verify.isUrl(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isDate : function(Response){
        var result = this.parent.Verify.isDate(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isRegex : function(Response, regex){
        regex = eval(regex);

        if( regex.test(Response.elmCurrValue) === false ){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isPassport : function(Response){
        var result = this.parent.Verify.isPassport(Response.elmCurrValue);

        if(result === false){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);
    },

    isSimplexEditorFill : function(Response){

        var result = eval(Response.elmCurrValue + ".isEmptyContent();");

        if(result === true){
            return this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
        }

        return this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

    },

    isMaxByte : function(Response, iLen) {
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        var len = this.parent.Helper.getByte(Response.elmCurrValue);

        if (len > iLen) {
            result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            result.msg = result.msg.replace(/\{max\}/i, iLen);
        }

        return result;
    },

    isMinByte : function(Response, iLen) {
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        var len = this.parent.Helper.getByte(Response.elmCurrValue);

        if (len < iLen) {
            result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            result.msg = result.msg.replace(/\{min\}/i, iLen);
        }

        return result;
    },

    isByteRange : function(Response, range) {
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        var rangeInfo = this._getRangeNum(range);
        var iMin = rangeInfo.min;
        var iMax = rangeInfo.max;

        var len = this.parent.Helper.getByte(Response.elmCurrValue);

        if (len < iMin || len > iMax) {
            result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            result.msg = result.msg.replace(/\{min\}/i, iMin);
            result.msg = result.msg.replace(/\{max\}/i, iMax);
        }

        return result;
    },

    isLengthRange : function(Response, range) {
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        var rangeInfo = this._getRangeNum(range);
        var iMin = rangeInfo.min;
        var iMax = rangeInfo.max;

        var resultMin = this.isMin(Response, iMin);
        var resultMax = this.isMax(Response, iMax);

        if (resultMin.passed === this.parent.CODE_FAIL || resultMax.passed === this.parent.CODE_FAIL) {
            result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            result.msg = result.msg.replace(/\{min\}/i, iMin);
            result.msg = result.msg.replace(/\{max\}/i, iMax);
        }

        return result;
    },

    isNumberMin : function(Response, iLimit) {
        var check = this.parent.Verify.isNumberMin(Response.elmCurrValue, iLimit);
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        if(check === false){
            result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            result.msg = result.msg.replace(/\{min\}/i, iLimit);
        }

        return result;
    },

    isNumberMax : function(Response, iLimit) {
        var check = this.parent.Verify.isNumberMax(Response.elmCurrValue, iLimit);
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        if(check === false){
            result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            result.msg = result.msg.replace(/\{max\}/i, iLimit);
        }

        return result;
    },

    isNumberRange : function(Response, range) {
        var iMin = range[0];
        var iMax = range[1];

        var check = this.parent.Verify.isNumberRange(Response.elmCurrValue, iMin, iMax);
        var result = this.parent.Helper.getResult(Response, this.parent.CODE_SUCCESS);

        if(check === false){
            result = this.parent.Helper.getResult(Response, this.parent.CODE_FAIL);
            result.msg = result.msg.replace(/\{min\}/i, iMin);
            result.msg = result.msg.replace(/\{max\}/i, iMax);
        }

        return result;
    },

    _getRangeNum : function(range) {
        var result = {};

        result.min = range[0] <= 0 ? 0 : parseInt(range[0]);
        result.max = range[1] <= 0 ? 0 : parseInt(range[1]);

        return result;
    },

    _fillConditionCheck : function(Response, cond) {
        var $ = this.parent.jQuery;
        var parent = this.parent;


        cond = parent.Helper.trim(cond);

        //조건식이 들어오면 조건식에 맞을 경우만 필수값을 체크함
        if (cond) {
            var conditions = cond.split('=');
            var fieldId = parent.Helper.trim(conditions[0]);
            var fieldVal = parent.Helper.trim(conditions[1]);

            try {
                var val = parent.Helper.getValue(Response.formId, $('#'+fieldId));
                val = parent.Helper.trim(val);

                if(fieldVal != val) {
                    return parent.Helper.getResult(Response, parent.CODE_SUCCESS);
                }
            } catch(e) {
                if (parent.DEBUG_MODE == true) {
                    Response.elmCurrErrorMsg = parent.msgs['isFillError'];
                    Response.elmCurrErrorMsg = Response.elmCurrErrorMsg.replace(/\{condition\}/i, cond);
                    return parent.Helper.getResult(Response, parent.CODE_FAIL);
                }

                return parent.Helper.getResult(Response, parent.CODE_SUCCESS);
            }
        }

        //Radio 나 Checkbox의 경우 선택한값이 있는지 여부를 체크함
        if (Response.elmCurrFieldType == 'radio' || Response.elmCurrFieldType == 'checkbox') {

            var sName = Response.elmCurrField.attr('name');
            var result = parent.Helper.getResult(Response, parent.CODE_FAIL);

            $('input[name="'+sName+'"]').each(function(i){
                if ($(this).get(0).checked === true) {
                    result = parent.Helper.getResult(Response, parent.CODE_SUCCESS);
                }
            });

            return result;

        }

        //일반 텍스트 박스
        if (Response.elmCurrValue != '') {
            return parent.Helper.getResult(Response, parent.CODE_SUCCESS);
        }

        return parent.Helper.getResult(Response, parent.CODE_FAIL);
    }
};

FwValidator.msgs = {

    //기본
    'isFill' : '{label} 항목은 필수 입력값입니다.',

    'isNumber' : '{label} 항목이 숫자 형식이 아닙니다.',

    'isEmail' : '{label} 항목이 이메일 형식이 아닙니다.',

    'isIdentity' : '{label} 항목이 아이디 형식이 아닙니다.',

    'isMax' : '{label} 을(를) {max}자 이하로 입력해주세요.',

    'isMin' : '{label} 항목이 {min}자(개) 이상으로 해주십시오 .',

    'isRegex' : '{label} 항목이 올바른 입력값이 아닙니다.',

    'isAlpha' : '{label} 항목이 영문이 아닙니다',

    'isAlphaLower' : '{label} 항목이 영문 소문자 형식이 아닙니다',

    'isAlphaUpper' : '{label} 항목이 영문 대문자 형식이 아닙니다',

    'isAlphaNum' : '{label} 항목이 영문이나 숫자 형식이 아닙니다.',

    'isAlphaNumLower' : '{label} 항목이 영문 소문자 혹은 숫자 형식이 아닙니다.',

    'isAlphaNumUpper' : '{label} 항목이 영문 대문자 혹은 숫자 형식이 아닙니다.',

    'isAlphaSpace' : '{label} 항목이 영문이 아닙니다',

    'isAlphaDash' : '{label} 항목이 [영문,숫자,_,-] 형식이 아닙니다.',

    'isAlphaDashLower' : '{label} 항목이 [영문 소문자,숫자,_,-] 형식이 아닙니다.',

    'isAlphaDashUpper' : '{label} 항목이 [영문 대문자,숫자,_,-] 형식이 아닙니다.',

    'isKorean' : '{label} 항목이 한국어 형식이 아닙니다.',

    'isUrl' : '{label} 항목이 URL 형식이 아닙니다.',

    'isSsn' : '{label} 항목이 주민등록번호 형식이 아닙니다.',

    'isForeignerNo' : '{label} 항목이 외국인등록번호 형식이 아닙니다.',

    'isBizNo' : '{label} 항목이 사업자번호 형식이 아닙니다.',

    'isPhone' : '{label} 항목이 전화번호 형식이 아닙니다.',

    'isMobile' : '{label} 항목이 핸드폰 형식이 아닙니다.',

    'isZipcode' : '{label} 항목이 우편번호 형식이 아닙니다.',

    'isJuriNo' : '{label} 항목이 법인번호 형식이 아닙니다.',

    'isIp' : '{label} 항목이 아이피 형식이 아닙니다.',

    'isDate' : '{label} 항목이 날짜 형식이 아닙니다.',

    'isMatch' : '{label} 항목과 {match} 항목이 같지 않습니다.',

    'isSuccess' : '{label} 항목의 데이터는 전송할 수 없습니다.',

    'isSimplexEditorFill' : '{label}(을/를) 입력하세요',

    'isPassport' : '{label} 항목이 여권번호 형식이 아닙니다.',

    'isMaxByte' : '{label} 항목은 {max}bytes 이하로 해주십시오.',

    'isMinByte' : '{label} 항목은 {min}bytes 이상으로 해주십시오.',

    'isByteRange' : '{label} 항목은 {min} ~ {max}bytes 범위로 해주십시오.',

    'isLengthRange' : '{label} 항목은 {min} ~ {max}자(개) 범위로 해주십시오.',

    'isNumberMin' : '{label} 항목은 {min} 이상으로 해주십시오.',

    'isNumberMax' : '{label} 항목은 {max} 이하로 해주십시오.',

    'isNumberRange' : '{label} 항목은 {min} ~ {max} 범위로 해주세요.',


    //디버깅
    'notMethod' : '{label} 항목에 존재하지 않는 필터를 사용했습니다.',

    'isFillError' : "[{label}] 필드의 isFill {condition} 문장이 잘못되었습니다.\r\n해당 필드의 아이디를 확인하세요."

};

FwValidator.Handler.overrideMsgs({

    //기본
    'isFill' : sprintf(__('IS.REQUIRED.FIELD', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isNumber' : sprintf(__('MAY.ONLY.CONTAIN.NUMBERS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isEmail' : sprintf(__('VALID.EMAIL.ADDRESS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isIdentity' : sprintf(__('FIELD.CORRECT.ID.FORMAT', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isMax' : sprintf(__('EXCEED.CHARACTERS.LENGTH', 'RESOUCE.JS.VALIDATOR'), '{label}', '{max}'),

    'isMin' : sprintf(__('MUST.AT.LEAST.CHARACTERS', 'RESOUCE.JS.VALIDATOR'), '{label}', '{min}'),

    'isRegex' : sprintf(__('FIELD.IN.CORRECT.FORMAT', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlpha' : sprintf(__('ALPHABETICAL.CHARACTERS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaLower' : sprintf(__('CONTAIN.LOWERCASE.LETTERS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaUpper' : sprintf(__('CONTAIN.UPPERCASE.LETTERS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaNum' : sprintf(__('ALPHANUMERIC.CHARACTERS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaNumLower' : sprintf(__('CONTAIN.LOWERCASE.LETTERS.001', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaNumUpper' : sprintf(__('CONTAIN.UPPERCASE.LETTERS.001', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaSpace' : sprintf(__('ALPHABETICAL.CHARACTERS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaDash' : sprintf(__('UNDERSCORES.DASHES', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaDashLower' : sprintf(__('UNDERSCORES.DASHES.001', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isAlphaDashUpper' : sprintf(__('UNDERSCORES.DASHES.002', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isKorean' : sprintf(__('CONTAIN.KOREAN.CHARACTERS', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isUrl' : sprintf(__('MUST.CONTAIN.VALID.URL', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isSsn' : sprintf(__('MUST.CONTAIN.VALID.SSN', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isForeignerNo' : sprintf(__('ALIEN.REGISTRATION.NUMBER', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isBizNo' : sprintf(__('REGISTRATION.NUMBER', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isPhone' : sprintf(__('VALID.PHONE.NUMBER', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isMobile' : sprintf(__('VALID.MOBILE.PHONE.NUMBER', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isZipcode' : sprintf(__('CONTAIN.VALID.ZIP.CODE', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isJuriNo' : sprintf(__('CORPORATE.IDENTITY.NUMBER', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isIp' : sprintf(__('MUST.CONTAIN.VALID.IP', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isDate' : sprintf(__('MUST.CONTAIN.VALID.DATE', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isMatch' : sprintf(__('THE.FIELD.DOES.NOT.MATCH', 'RESOUCE.JS.VALIDATOR'), '{label}', '{match}'),

    'isSuccess' : sprintf(__('THE.DATA.BE.TRANSFERRED', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isSimplexEditorFill' : sprintf(__('THE.FIELD.MUST.HAVE.VALUE', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isPassport' : sprintf(__('VALID.PASSPORT.NUMBER', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isMaxByte' : sprintf(__('VALUE.CAN.NOT.EXCEED', 'RESOUCE.JS.VALIDATOR'), '{label}', '{max}'),

    'isMinByte' : sprintf(__('THE.FIELD.VALUE.MUST.BE', 'RESOUCE.JS.VALIDATOR'), '{label}', '{min}'),

    'isByteRange' : sprintf(__('THE.FIELD.VALUE.MUST.BE.001', 'RESOUCE.JS.VALIDATOR'), '{label}', '{min}', '{max}'),

    'isLengthRange' : sprintf(__('MUST.CHARACTERS.LENGTH', 'RESOUCE.JS.VALIDATOR'), '{label}', '{min}', '{max}'),

    'isNumberMin' : sprintf(__('THE.FIELD.VALUE.MUST.BE.002', 'RESOUCE.JS.VALIDATOR'), '{label}', '{min}'),

    'isNumberMax' : sprintf(__('VALUE.CAN.NOT.EXCEED.001', 'RESOUCE.JS.VALIDATOR'), '{label}', '{max}'),

    'isNumberRange' : sprintf(__('THE.FIELD.VALUE.MUST.BE.003', 'RESOUCE.JS.VALIDATOR'), '{label}', '{min}', '{max}'),


    //디버깅
    'notMethod' : sprintf(__('FILTER.WAS.USED.FIELD', 'RESOUCE.JS.VALIDATOR'), '{label}'),

    'isFillError' : sprintf(__('SENTENCE.INCORRECT.PLEASE', 'RESOUCE.JS.VALIDATOR'), '{label}', '{condition}')

});
/**
 * 엘리먼트 종류별 값 가져오기 form 에 의한 동일한 name 값 구별
 *
 * - 오브젝트를 받아서 사용할 수 있게함.
 *
 * @param String id
 * @return
 * @author 박난하 <nhpark@simplexi.com>, 백충덕 <cdbaek@simplexi.com>, 이재욱 <jwlee03@simplexi.com>
 */
AuthSSLManager.getValue = function(id) {
    //id 가 string인 경우
    if (typeof id == 'string') {
        var divide, o, type;

        divide = id.split('::');
        if (divide.length == 1) {
            o = document.getElementsByName(id);
        } else {
            var frm = divide[0], id = divide[1];

            // radio, checkbox
            if (EC$('#'+ EC$.escapeSelector(id)).length==0) {
                val = this.checkbox({'name': id, 'mode': 'val'});
                return val;
            }
            o = document.forms[frm][id];
        }

        if ( o == null || o == undefined || o.value == null || o.value == undefined ) {
            o = document.getElementsByName(id);
            // 전체 html 에선 id 값이 있지만 form 밖에 있을수 있으므로 조건추가 (ECHOSTING-265537)
            val = (o[0] == undefined) ? '' : o[0].value;
        } else {
            val = o.value;
        }

        return val;

    } else if (typeof id == 'object') {
        //id가 object인 경우

        //오직 하나의 오브젝트에 대해서만 처리
        if (EC$(id).length == 1) {
            return EC$(id).val();
        } else {
            return '';
        }

    } else {
        // id가 string 또는 object가 아닐 경우 빈 값 리턴
        return '';
    }
};

/**
 * 엘리먼트 종류별 값 가져오기 form 에 의한 동일한 name 값 구별
 * @param String id
 * @return
 * @author 박난하 <nhpark@simplexi.com>, 백충덕 <cdbaek@simplexi.com>
 */
AuthSSLManager.getValuePay = function(id) {
    var divide, o, type;

    // id가 string이 아닐 경우 빈 값 리턴
    if (typeof id != 'string') return '';

    divide = id.split('::');
    var frm = divide[0], id = divide[1];

    // radio, checkbox
    if (EC$('#'+id).length==0) {
        val = this.checkbox({'name': id, 'mode': 'val'});
        return val;
    }

    o = document.forms[frm][id];

    if ( o == null || o == undefined || o.value == null || o.value == undefined ) {
        o = document.getElementsByName(id);
        val = o[0].value;
    } else {
        val = o.value;
    }

    return val;
};

/**
 * 암호화 param 데이터 세팅
 * @param array param 암호화 관련
 * @return string p 암호화 param
 * @author 박난하 <nhpark@simplexi.com>
 * */
AuthSSLManager.setParam = function(param) {
    var p = [];
        if (param['auth_mode'] == 'encrypt1.9') {
            p.push('auth_mode=encrypt');
        } else {
            p.push('auth_mode=' + param['auth_mode']);
        }
        p.push('auth_callbackName=' + param['auth_callbackName']);
    switch(param['auth_mode']) {
        case 'encrypt1.9':
            var aEle = param['aEleId'], o, p2 = {}, v;
            var divide = '';
            var id = '';
            for ( var i in aEle ) {
                if (aEle.hasOwnProperty(i) == false) continue;
                v = this.getValuePay(aEle[i]);

                if ( v == -1 ) continue;

                divide = aEle[i].split('::');
                id = divide[1];

                p2[id] = this.getValuePay(aEle[i]);
            }
            p.push('auth_string=' + encodeURIComponent(__JSON.stringify(p2)));
            break;
        case 'encrypt':
            var aEle = param['aEleId'], o, p2 = {}, v;
            for ( var i in aEle ) {
                if (aEle.hasOwnProperty(i) == false) continue;
                v = this.getValue(aEle[i]);

                if ( v == -1 ) continue;

                //암호화 대상이 오브젝트인경우 id값이 key가 된다.
                if (typeof aEle[i] == 'object') {
                    p2[EC$(aEle[i]).attr('id')] = this.getValue(aEle[i]);
                } else {
                    p2[aEle[i]] = this.getValue(aEle[i]);
                }
            }
            p.push('auth_string=' + encodeURIComponent(__JSON.stringify(p2)));
            break;
        case 'decrypt':
        case 'decryptClient':
            p.push('auth_string=' + encodeURIComponent(param['auth_string']));
            break;
    }

    return p;
};


/**
 * radio, checkbox 값 가져오기
 * @param object options 옵션
 * @return string radio 또는 checkbox value 반환
 * @author 박난하 <nhpark@simplexi.com>, 백충덕 <cdbaek@simplexi.com>
 * */
AuthSSLManager.checkbox = function(options)
{
    var o = document.getElementsByName(options.name);
    if ( o == null ) return;

    // element 없음
    if (o.length<1) {
        var chk = false;
        var o = document.getElementById(options.name);
        if ( o == null ) return '';
        if ( o.checked == true ) var chk = true;
        return chk == true ? o.value : '';
    }

    var bChecked = false;
    var aChk = new Array();
    for ( var i = 0; i < o.length; i++ ) {
        var el = EC$('#'+o[i].id);

        if ( el.prop('checked') == true ) {
            // RADIO
            if (el.prop('type') == 'radio') return el.val();
            // CHECKBOX
            else if (el.prop('type') == 'checkbox') {
                aChk.push(el.val());
                bChecked = true;
            }
        }
    }

    if ( options.mode == 'val' ) {
        if (bChecked == false) return '';

        if (aChk.length>0) return aChk.join('|');
    }
};






/**
 * AuthSSL을 통해 submit을 할 폼 클래스
 * @author 백충덕 <cdbaek@simplexi.com>
 * @since 2011. 6. 16
 * */
var FormSSL = function()
{
    /**
     * 폼 아이디
     * @var string
     * */
    this.sFormId = null;
    /**
     * 암호화 시킬 엘리먼트 id 배열
     * @var array
     * */
    this.aEleId  = null;

    /**
     * onsubmit bind
     * @param string sFormId bind 할 폼 아이디
     * @param array  aEleId  암호화할 엘리먼트 id 배열
     * */
    this.bind = function(sFormId, aEleId)
    {
        var self = this;

        this.sFormId = sFormId;
        this.aEleId  = aEleId;

        var oForm = EC$('#'+sFormId);
        oForm.off('submit');
        oForm.on('submit', function(){
            AuthSSL.Submit(self.sFormId, self.aEleId);

            return false;
        });
    };

    /**
     * AuthSSL submit 실행
     * */
    this.submit = function()
    {
        AuthSSL.Submit(this.sFormId, this.aEleId);
        return false;
    };
};


/**
 * AuthSSL 폼 객체 리스트 관리
 * @author 백충덕 <cdbaek@simplexi.com>
 * @since 2011. 6. 16
 * */
var FormSSLContainer = {
    /**
     * 폼 객체 리스트
     * @var object
     * */
    aFormSSL: {},

    /**
     * 폼 객체 생성 및 리스트에 추가
     * @param string sFormId 객체로 생성할 폼 아이디
     * @param array  aEleId  암호화 할 엘리먼트 아이디
     * */
    create: function (sFormId, aEleId)
    {
        if (this.formExists(sFormId)==false) {
            var oFormSSL = new FormSSL();
            oFormSSL.bind(sFormId, aEleId);
            this.aFormSSL[sFormId] = oFormSSL;
        }
    },

    /**
     * 폼 아이디로 AuthSSL submit 실행
     * @param string sFormId 폼 아이디
     * */
    submit: function (sFormId)
    {
        if (this.formExists(sFormId)==false) return;

        this.aFormSSL[sFormId].submit();
    },

    /**
     * 폼 아이디로 FormSSLContainer에 해당 폼이 있는지 체크
     * @param string sFormId 체크할 폼 아이디
     * @return bool 폼이 있으면 true, 없으면 false
     * */
    formExists: function (sFormId)
    {
        if (!this.aFormSSL[sFormId]) return false;
        else return true;
    }
};



/**
 * AuthSSL 클래스
 * @author 백충덕 <cdbaek@simplexi.com>
 * @since 2011. 6. 16
 * */
var AuthSSL = {
    /**
     * SSL on/off
     * @var bool
     * */
    bIsSsl : true,
    /**
     * 폼 아이디
     * @var string
     * */
    sFormId : null,
    /**
     * 엘리먼트 아이디
     * @var array
     * */
    aEleId : null,
    /**
     * 폼 객체 (jQuery)
     * @var object
     * */
    oFormSubmit: null,
    /**
     * 암호화 된 문자열이 저장될 input hidden id
     * @var string
     * */
    sEncryptId : 'encrypted_str',
    /**
     * callback 함수 이름
     * @var string
     * */
    sCallbackName : 'AuthSSL.encryptSubmit_Complete',

    /**
     * 멤버변수 세팅
     * @param string sFormId 폼 아이디
     * @param array  aEleId  엘리먼트 아이디
     * */
    init: function(sFormId, aEleId)
    {
        this.sFormId = sFormId;
        this.aEleId  = aEleId;
        this.oFormSubmit = EC$('#' + sFormId);
    },

    /**
     * AuthSSLManager 존재여부 체크
     * @return bool 존재하면 true, 아니면 false 반환
     * */
    checkAvailable: function()
    {
        // AuthSSLManager가 없음
        if (typeof AuthSSLManager!='object') {
            alert('[Error]\nneed SSL Manager');
            return false;
        }

        return true;
    },

    /**
     * onsubmit bind
     * @param string sFormId 폼 아이디
     * @param array  aEleId  암호화하고자 하는 필드의 id
     * */
    Bind: function(sFormId, aEleId)
    {
        FormSSLContainer.create(sFormId, aEleId);
    },

    /**
     * 암호화 요청 함수 - submit
     * @param string sFormId 폼 아이디
     * @param array  aEleId  엘리먼트 아이디
     * */
    Submit: function(sFormId, aEleId) {
        // AuthSSLManager 존재여부 체크
        if (this.checkAvailable()==false) return false;

        // 폼 아이디, 엘리먼트 아이디 세팅
        this.init(sFormId, aEleId);

        // 암호화 요청이 아닐 경우 그냥 submit
        if (this.bIsSsl == false) {
            this.disabledSslSubmit();
            return false;
        }

        // 암호화 된 값을 받을 input_hidden 생성
        var oInput = document.createElement('input');
        oInput.type = 'hidden';
        oInput.name = oInput.id = this.sEncryptId;
        this.oFormSubmit.append(oInput);

        // 암호화 요청
        this.encrypt(this.aEleId, this.sCallbackName);
    },

    /**
     * 암호화 요청
     * @param array aEleId 암호화할 엘리먼트 id
     * @param string sCallbackName 콜백함수 이름
     * */
    encrypt: function(aEleId, sCallbackName) {
        AuthSSLManager.weave({
            'auth_mode'        : 'encrypt',
            'aEleId'           : aEleId,
            'auth_callbackName': sCallbackName
        });
    },

    /**
     * 암호화 처리결과 callback 함수
     * @param string sOutput 암호화 된 처리결과
     * */
    encryptSubmit_Complete: function(sOutput) {
        // Error
        if ( AuthSSLManager.isError(sOutput) == true ) {
            alert('[Error]\n'+sOutput);
            return;
        }

        // 암호화 처리된 엘리먼트의 value 제거
        this.delInputValue();

        // input_hidden에 암호화 된 결과값 대입
        this.oFormSubmit.find('[id="'+this.sEncryptId+'"]').val(sOutput);

        // Form Submit
        this.oFormSubmit.off('submit');

        this.delInputValue();

        this.oFormSubmit.submit();
    },

    /**
     * INPUT.RADIO, INPUT.CHECKBOX의 value 지움
     * @param string sName 값을 지우고자 하는 element의 name
     * */
    delRadioValue: function(sName) {
        var oEle = document.getElementsByName(sName);
        if (oEle.length>0) {

            for (var i = 0; i < oEle.length; i++) {

                oEle[i].value = '';

                if (oEle[i].defaultValue) {

                    oEle[i].defaultValue = '';
                }
            }
        }
    },

    /**
     * 암호화 될 폼 요소들의 값을 지움
     */
    delInputValue : function() {
        for (var i=0; i<this.aEleId.length; i++) {

            // 값을 지울 element의 id 가져오기
            var sID = AuthSSL.getEleId(this.aEleId[i]);
            var oEle = this.oFormSubmit.find('[id="' + sID + '"]');

            // id를 표기하지 않고 name만 표기한 radio, checkbox
            if (oEle.length == 0) {

                this.delRadioValue(sID);
                continue;
            }

            // SELECT
            if (oEle.is('SELECT')) {

                var oSelect = oEle.children('option:selected');
                oSelect.val('');
                oSelect.prop('value', '');
                oSelect.prop('defaultValue', '');
            }
            // INPUT.TEXT, INPUT.PASSWORD, TEXTAREA
            else {

                oEle.val('');
                oEle.prop('value', '');
                oEle.prop('defaultValue', '');
            }
        } // for
    },

    /**
     * 넘겨받은 id에서 폼 아이디와 구분자를 제거하여 가져오기
     * @param string sOrgID 원본 폼 아이디
     * @return string 폼 아이디와 구분자가 제거된 아이디 반환
     * */
    getEleId: function(sOrgID)
    {
        var sID = sOrgID;
        if (/::/.test(sID)==true) {
            var aTmp = sID.split('::');
            sID = aTmp[1];
        }

        return sID;
    },

    /**
     * bIsSsl이 false 일때 실행
     */
    disabledSslSubmit : function() {
        this.oFormSubmit.off('submit');
        this.oFormSubmit.submit();
    }
};


// validator submit hook
EC$(function(){
    if (typeof FwValidator == 'undefined') return;

    FwValidator.Handler.setBeforeSubmit(function(elmForm){
        // AuthSSL 사용폼
        if (FormSSLContainer.formExists(elmForm.attr('id'))==true) {
            if (!FormSSLContainer) return true;

            FormSSLContainer.submit(elmForm.attr('id'));
            return false;
        }

        // AuthSSL 사용폼이 아닐 경우 그냥 submit
        return true;
    });
});

/**
 * 접속통계 & 실시간접속통계
 */
EC$(function(){
    // 이미 weblog.js 실행 되었을 경우 종료 
    if (EC$('#log_realtime').length > 0) {
        return;
    }
    /*
     * QueryString에서 디버그 표시 제거
     */
    function stripDebug(sLocation)
    {
        if (typeof sLocation != 'string') return '';

        sLocation = sLocation.replace(/^d[=]*[\d]*[&]*$/, '');
        sLocation = sLocation.replace(/^d[=]*[\d]*[&]/, '');
        sLocation = sLocation.replace(/(&d&|&d[=]*[\d]*[&]*)/, '&');

        return sLocation;
    }

    // 벤트 몰이 아닐 경우에만 V3(IFrame)을 로드합니다.
    // @date 190117
    // @date 191217 - 이벤트에도 V3 상시 적재로 변경.
    //if (EC_FRONT_JS_CONFIG_MANAGE.sWebLogEventFlag == "F")
    //{
    // T 일 경우 IFRAME 을 노출하지 않는다.
    if (EC_FRONT_JS_CONFIG_MANAGE.sWebLogOffFlag == "F")
    {
        if (window.self == window.top) {
            var rloc = escape(document.location);
            var rref = escape(document.referrer);
        } else {
            var rloc = (document.location).pathname;
            var rref = '';
        }

        // realconn & Ad aggregation
        var _aPrs = new Array();
        _sUserQs = window.location.search.substring(1);
        _sUserQs = stripDebug(_sUserQs);
        _aPrs[0] = 'rloc=' + rloc;
        _aPrs[1] = 'rref=' + rref;
        _aPrs[2] = 'udim=' + window.screen.width + '*' + window.screen.height;
        _aPrs[3] = 'rserv=' + aLogData.log_server2;
        _aPrs[4] = 'cid=' + eclog.getCid();
        _aPrs[5] = 'role_path=' + EC$('meta[name="path_role"]').attr('content');
        _aPrs[6] = 'stype=' + aLogData.stype;
        _aPrs[7] = 'shop_no=' + aLogData.shop_no;
        _aPrs[8] = 'lang=' + aLogData.lang;
        _aPrs[9] = 'ver=' + aLogData.ver;


        // 모바일웹일 경우 추가 파라미터 생성
        var _sMobilePrs = '';
        if (mobileWeb === true) _sMobilePrs = '&mobile=T&mobile_ver=new';

        _sUrlQs = _sUserQs + '&' + _aPrs.join('&') + _sMobilePrs;

        var _sUrlFull = '/exec/front/eclog/main/?' + _sUrlQs;

        var node = document.createElement('iframe');
        node.setAttribute('src', _sUrlFull);
        node.setAttribute('id', 'log_realtime');
        document.body.appendChild(node);

        EC$('#log_realtime').hide();
    }

    // eclog2.0, eclog1.9
    var sTime = new Date().getTime();//ECHOSTING-54575

    // 접속통계 서버값이 있다면 weblog.js 호출
    if (aLogData.log_server1 != null && aLogData.log_server1 != '') {
        var sScriptSrc = '//' + aLogData.log_server1 + '/weblog.js?uid=' + aLogData.mid + '&uname=' + aLogData.mid + '&r_ref=' + document.referrer + '&shop_no=' + aLogData.shop_no;
        if (mobileWeb === true) sScriptSrc += '&cafe_ec=mobile';
        sScriptSrc += '&t=' + sTime;//ECHOSTING-54575
        var node = document.createElement('script');
        node.setAttribute('type', 'text/javascript');
        node.setAttribute('src', sScriptSrc);
        node.setAttribute('id', 'log_script');
        document.body.appendChild(node);
    }

    // CA (Cafe24 Analytics
    if (aLogData.ca != null) {
        (function (i, s, o, g, r, a, m, n, d) {
            i['cfaObject'] = g;
            i['cfaUid'] = r;
            i['cfaStype'] = a;
            i['cfaDomain'] = m;
            i['cfaSno'] = n;
            i['cfaEtc'] = d;
            a = s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m);
        })(window, document, 'script', '//' + aLogData.ca +'?v=' + sTime, aLogData.mid, aLogData.stype, aLogData.domain, aLogData.shop_no, aLogData.etc);
    }
});

/**
 * 쇼핑몰 금액 라이브러리
 */
var SHOP_PRICE = {

    /**
     * iShopNo 쇼핑몰의 결제화폐에 맞게 리턴합니다.
     * @param float fPrice 금액
     * @param bool bIsNumberFormat number_format 적용 유무
     * @param int iShopNo 쇼핑몰번호
     * @return float|string
     */
    toShopPrice: function(fPrice, bIsNumberFormat, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        // 결제화폐 정보
        var aCurrencyInfo = SHOP_CURRENCY_INFO[iShopNo].aShopCurrencyInfo;

        return SHOP_PRICE.toPrice(fPrice, aCurrencyInfo, bIsNumberFormat);
    },

    /**
     * iShopNo 쇼핑몰의 참조화폐에 맞게 리턴합니다.
     * @param float fPrice 금액
     * @param bool bIsNumberFormat number_format 적용 유무
     * @param int iShopNo 쇼핑몰번호
     * @return float|string
     */
    toShopSubPrice: function(fPrice, bIsNumberFormat, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        // 참조화폐 정보
        var aSubCurrencyInfo = SHOP_CURRENCY_INFO[iShopNo].aShopSubCurrencyInfo;

        if ( ! aSubCurrencyInfo) {
            // 참조화폐가 없으면
            return '';

        } else {
            // 결제화폐 정보
            var aCurrencyInfo = SHOP_CURRENCY_INFO[iShopNo].aShopCurrencyInfo;
            if (aSubCurrencyInfo.currency_code === aCurrencyInfo.currency_code) {
                // 결제화폐와 참조화폐가 동일하면
                return '';
            } else {
                return SHOP_PRICE.toPrice(fPrice, aSubCurrencyInfo, bIsNumberFormat);
            }
        }
    },

    /**
     * 쇼핑몰의 기준화폐에 맞게 리턴합니다.
     * @param float fPrice 금액
     * @param bool bIsNumberFormat number_format 적용 유무
     * @param int iShopNo 쇼핑몰번호
     * @return float
     */
    toBasePrice: function(fPrice, bIsNumberFormat, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        // 기준화폐 정보
        var aBaseCurrencyInfo = SHOP_CURRENCY_INFO[iShopNo].aBaseCurrencyInfo;

        return SHOP_PRICE.toPrice(fPrice, aBaseCurrencyInfo, bIsNumberFormat);
    },

    /**
     * 결제화폐 금액을 참조화폐 금액으로 변환하여 리턴합니다.
     * @param float fPrice 금액
     * @param bool bIsNumberFormat number_format 적용 유무
     * @param int iShopNo 쇼핑몰번호
     * @return float 참조화폐 금액
     */
    shopPriceToSubPrice: function(fPrice, bIsNumberFormat, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        // 결제화폐 금액 => 참조화폐 금액
        fPrice = fPrice * (SHOP_CURRENCY_INFO[iShopNo].fExchangeSubRate || 0);

        return SHOP_PRICE.toShopSubPrice(fPrice, bIsNumberFormat, iShopNo);
    },

    /**
     * 결제화폐 대비 기준화폐 환율 리턴
     * @param int iShopNo 쇼핑몰번호
     * @return float 결제화폐 대비 기준화폐 환율
     */
    getRate: function(iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        return SHOP_CURRENCY_INFO[iShopNo].fExchangeRate;
    },

    /**
     * 결제화폐 대비 참조화폐 환율 리턴
     * @param int iShopNo 쇼핑몰번호
     * @return float 결제화폐 대비 참조화폐 환율 (참조화폐가 없는 경우 null을 리턴합니다.)
     */
    getSubRate: function(iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        return SHOP_CURRENCY_INFO[iShopNo].fExchangeSubRate;
    },

    /**
     * 금액을 원하는 화폐코드의 제약조건(소수점 절삭)에 맞춰 리턴합니다.
     * @param float fPrice 금액
     * @param string aCurrencyInfo 원하는 화폐의 화폐 정보
     * @param bool bIsNumberFormat number_format 적용 유무
     * @return float|string
     */
    toPrice: function(fPrice, aCurrencyInfo, bIsNumberFormat)
    {
        // 소수점 아래 절삭
        var iPow = Math.pow(10, aCurrencyInfo['decimal_place']);
        fPrice = fPrice * iPow;
        if (aCurrencyInfo['round_method_type'] === 'F') {
            fPrice = Math.floor(fPrice);
        } else if (aCurrencyInfo['round_method_type'] === 'C') {
            fPrice = Math.ceil(fPrice);
        } else {
            fPrice = Math.round(fPrice);
        }
        fPrice = fPrice / iPow;

        if ( ! fPrice) {
            // 가격이 없는 경우
            return 0;

        } else if (bIsNumberFormat === true) {
            // 3자리씩 ,로 끊어서 리턴
            var sPrice = fPrice.toFixed(aCurrencyInfo['decimal_place']);
            var regexp = /^(-?[0-9]+)([0-9]{3})($|\.|,)/;
            while (regexp.test(sPrice)) {
                sPrice = sPrice.replace(regexp, "$1,$2$3");
            }
            return sPrice;

        } else {
            // 숫자만 리턴
            return fPrice;

        }
    }    
};

/**
 * 화폐 포맷
 */
var SHOP_CURRENCY_FORMAT = {
    /**
     * 어드민 페이지인지
     * @var bool
     */
    _bIsAdmin: /^\/(admin\/php|disp\/admin|exec\/admin)\//.test(location.pathname) ? true : false,

    /**
     * iShopNo 쇼핑몰의 결제화폐 포맷을 리턴합니다.
     * @param int iShopNo 쇼핑몰번호
     * @return array head,tail
     */
    getShopCurrencyFormat: function(iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        // 결제화폐 코드
        var sCurrencyCode = SHOP_CURRENCY_INFO[iShopNo].aShopCurrencyInfo.currency_code;

        if (SHOP_CURRENCY_FORMAT._bIsAdmin === true) {
            // 어드민

            // 기준화폐 코드
            var sBaseCurrencyCode = SHOP_CURRENCY_INFO[iShopNo].aBaseCurrencyInfo.currency_code;

            if (sCurrencyCode === sBaseCurrencyCode) {
                // 결제화폐와 기준화폐가 동일한 경우
                return {
                    'head': '',
                    'tail': ''
                };

            } else {
                return {
                    'head': sCurrencyCode + ' ',
                    'tail': ''
                };
            }

        } else {
            // 프론트
            return SHOP_CURRENCY_INFO[iShopNo].aFrontCurrencyFormat;
        }
    },

    /**
     * iShopNo 쇼핑몰의 참조화폐의 포맷을 리턴합니다.
     * @param int iShopNo 쇼핑몰번호
     * @return array head,tail
     */
    getShopSubCurrencyFormat: function(iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        // 참조화폐 정보
        var aSubCurrencyInfo = SHOP_CURRENCY_INFO[iShopNo].aShopSubCurrencyInfo;

        if ( ! aSubCurrencyInfo) {
            // 참조화폐가 없으면
            return {
                'head': '',
                'tail': ''
            };

        } else if (SHOP_CURRENCY_FORMAT._bIsAdmin === true) {
            // 어드민
            return {
                'head': '(' + aSubCurrencyInfo.currency_code + ' ',
                'tail': ')'
            };

        } else {
            // 프론트
            return SHOP_CURRENCY_INFO[iShopNo].aFrontSubCurrencyFormat;
        }

    },

    /**
     * 쇼핑몰의 기준화폐의 포맷을 리턴합니다.
     * @param int iShopNo 쇼핑몰번호
     * @return array head,tail
     */
    getBaseCurrencyFormat: function(iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        // 기준화폐 코드
        var sBaseCurrencyCode = SHOP_CURRENCY_INFO[iShopNo].aBaseCurrencyInfo.currency_code;

        // 결제화폐 코드
        var sCurrencyCode = SHOP_CURRENCY_INFO[iShopNo].aShopCurrencyInfo.currency_code;

        if (sCurrencyCode === sBaseCurrencyCode) {
            // 기준화폐와 결제화폐가 동일하면
            return {
                'head': '',
                'tail': ''
            };

        } else {
            // 어드민
            return {
                'head': '(' + sBaseCurrencyCode + ' ',
                'tail': ')'
            };

        }
    },

    /**
     * 금액 입력란 화폐 포맷용 head,tail
     * @param int iShopNo 쇼핑몰번호
     * @return array head,tail
     */
    getInputFormat: function(iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var sCurrencyCode = SHOP_CURRENCY_INFO[iShopNo].aShopCurrencyInfo;

        // 멀티쇼핑몰이 아니고 단위가 '원화'인 경우
        if (SHOP.isMultiShop() === false && sCurrencyCode === 'KRW') {
            return {
                'head': '',
                'tail': '원'
            };

        } else {
            return {
                'head': '',
                'tail': sCurrencyCode
            };
        }
    },

    /**
     * 해당몰 결제 화폐 코드 반환
     * ECHOSTING-266141 대응
     * 국문 기본몰 일 경우에는 화폐코드가 아닌 '원' 으로 반환
     *
     * @param int iShopNo 쇼핑몰번호
     * @return string currency_code
     */
    getCurrencyCode: function(iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var sCurrencyCode = SHOP_CURRENCY_INFO[iShopNo].aShopCurrencyInfo.currency_code;

        // 멀티쇼핑몰이 아니고 단위가 '원화'인 경우
        if (SHOP.isMultiShop() === false && sCurrencyCode === 'KRW') {
            return '원';
        } else {
            return sCurrencyCode;
        }
    }

};

/**
 * 금액 포맷
 */
var SHOP_PRICE_FORMAT = {
    /**
     * iShopNo 쇼핑몰의 결제화폐에 맞도록 하고 포맷팅하여 리턴합니다.
     * @param float fPrice 금액
     * @param int iShopNo 쇼핑몰번호
     * @return string
     */
    toShopPrice: function(fPrice, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var aFormat = SHOP_CURRENCY_FORMAT.getShopCurrencyFormat(iShopNo);
        var sPrice = SHOP_PRICE.toShopPrice(fPrice, true, iShopNo);
        return aFormat.head + sPrice + aFormat.tail;
    },

    /**
     * iShopNo 쇼핑몰의 참조화폐에 맞도록 하고 포맷팅하여 리턴합니다.
     * @param float fPrice 금액
     * @param int iShopNo 쇼핑몰번호
     * @return string
     */
    toShopSubPrice: function(fPrice, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var aFormat = SHOP_CURRENCY_FORMAT.getShopSubCurrencyFormat(iShopNo);
        var sPrice = SHOP_PRICE.toShopSubPrice(fPrice, true, iShopNo);
        return aFormat.head + sPrice + aFormat.tail;
    },

    /**
     * 쇼핑몰의 기준화폐에 맞도록 하고 포맷팅하여 리턴합니다.
     * @param float fPrice 금액
     * @param int iShopNo 쇼핑몰번호
     * @return string
     */
    toBasePrice: function(fPrice, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var aFormat = SHOP_CURRENCY_FORMAT.getBaseCurrencyFormat(iShopNo);
        var sPrice = SHOP_PRICE.toBasePrice(fPrice, true, iShopNo);
        return aFormat.head + sPrice + aFormat.tail;
    },

    /**
     * 결제화폐 금액을 참조화폐 금액으로 변환하고 포맷팅하여 리턴합니다.
     * @param float fPrice 금액
     * @param int iShopNo 쇼핑몰번호
     * @return string
     */
    shopPriceToSubPrice: function(fPrice, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var aFormat = SHOP_CURRENCY_FORMAT.getShopSubCurrencyFormat(iShopNo);
        var sPrice = SHOP_PRICE.shopPriceToSubPrice(fPrice, true, iShopNo);
        return aFormat.head + sPrice + aFormat.tail;
    },
    

    /**
     * 금액을 적립금 단위 명칭 설정에 따라 반환
     * @param float fPrice 금액
     * @return float|string
     */
    toShopMileagePrice: function (fPrice, iShopNo) {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;
        
        var sPrice = SHOP_PRICE.toShopPrice(fPrice, true, iShopNo);
        if (typeof sMileageUnit != 'undefined' && EC_UTIL.trim(sMileageUnit) != '') {
            sConvertMileageUnit = sMileageUnit.replace('[:PRICE:]', sPrice);
            return sConvertMileageUnit;
        } else {
            return SHOP_PRICE_FORMAT.toShopPrice(fPrice);
        }
    },

    /**
     * 금액을 예치금 단위 명칭 설정에 따라 반환
     * @param float fPrice 금액
     * @return float|string
     */
    toShopDepositPrice: function (fPrice, iShopNo) {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;
        
        var sPrice = SHOP_PRICE.toShopPrice(fPrice, true, iShopNo);
        if (typeof sDepositUnit != 'undefined' || EC_UTIL.trim(sDepositUnit) != '') {
            return sPrice + sDepositUnit;
        } else {
            return SHOP_PRICE_FORMAT.toShopPrice(fPrice);
        }
    },

    /**
     * 금액을 부가결제수단(통합포인트) 단위 명칭 설정에 따라 반환
     * @param float fPrice 금액
     * @return float|string
     */
    toShopAddpaymentPrice: function (fPrice, sAddpaymentUnit, iShopNo) {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var sPrice = SHOP_PRICE.toShopPrice(fPrice, true, iShopNo);
        if (typeof sDepositUnit != 'undefined' || EC_UTIL.trim(sDepositUnit) != '') {
            return sPrice + sAddpaymentUnit;
        } else {
            return SHOP_PRICE_FORMAT.toShopPrice(fPrice);
        }
    },

    /**
     * 포맷을 제외한 금액정보만 리턴합니다.
     * @param {string} sFormattedPrice
     * @returns {string}
     */
    detachFormat: function(sFormattedPrice) {
        if (typeof sFormattedPrice === 'undefined' || sFormattedPrice === null) {
            return '0';
        }

        var sPattern = /[0-9.]/;
        var sPrice = '';
        for (var i = 0; i < sFormattedPrice.length; i++) {
            if (sPattern.test(sFormattedPrice[i])) {
                sPrice += sFormattedPrice[i];
            }
        }

        return sPrice;
    }
};

var SHOP_PRICE_UTIL = {
    /**
     * iShopNo 쇼핑몰의 결제화폐 금액 입력폼으로 만듭니다.
     * @param Element elem 입력폼
     * @param bool bUseMinus 마이너스 입력 사용 여부
     */
    toShopPriceInput: function(elem, iShopNo, bUseMinus)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var iDecimalPlace = SHOP_CURRENCY_INFO[iShopNo].aShopCurrencyInfo.decimal_place;
        bUseMinus ? SHOP_PRICE_UTIL._toPriceInput(elem, iDecimalPlace, bUseMinus) : SHOP_PRICE_UTIL._toPriceInput(elem, iDecimalPlace);
    },

    /**
     * iShopNo 쇼핑몰의 참조화폐 금액 입력폼으로 만듭니다.
     * @param Element elem 입력폼
     */
    toShopSubPriceInput: function(elem, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var iDecimalPlace = SHOP_CURRENCY_INFO[iShopNo].aShopSubCurrencyInfo.decimal_place;
        SHOP_PRICE_UTIL._toPriceInput(elem, iDecimalPlace);
    },

    /**
     * iShopNo 쇼핑몰의 기준화폐 금액 입력폼으로 만듭니다.
     * @param Element elem 입력폼
     */
    toBasePriceInput: function(elem, iShopNo)
    {
        iShopNo = parseInt(iShopNo) || EC_SDE_SHOP_NUM;

        var iDecimalPlace = SHOP_CURRENCY_INFO[iShopNo].aBaseCurrencyInfo.decimal_place;
        SHOP_PRICE_UTIL._toPriceInput(elem, iDecimalPlace);
    },

    /**
     * 소수점 iDecimalPlace까지만 입력 가능하도록 처리
     * @param Element elem 입력폼
     * @param int iDecimalPlace 허용 소수점
     * @param bool bUseMinus 마이너스 입력 사용 여부
     */
    _toPriceInput: function(elem, iDecimalPlace, bUseMinus)
    {
        attachEvent(elem, 'keyup', function(e) {
            e = e || window.event;
            bUseMinus ? replaceToMinusPrice(e.srcElement) : replaceToPrice(e.srcElement);
        });
        attachEvent(elem, 'blur', function(e) {
            e = e || window.event;
            bUseMinus ? replaceToMinusPrice(e.srcElement) : replaceToPrice(e.srcElement);
        });

        // 추가금액에서 마이너스를 입력받기 위해 사용
        function replaceToMinusPrice(target) {
            var value = target.value;

            var regExpTest = new RegExp('^[0-9]*' + (iDecimalPlace ? '' : '\\.[0-9]{0, ' + iDecimalPlace + '}' ) + '$');

            if (regExpTest.test(value) === false) {
                value = value.replace(/[^0-9.|\-]/g, '');
                if (parseInt(iDecimalPlace)) {
                    value = value.replace(/^([0-9]+\.[0-9]+)\.+.*$/, '$1');
                    value = value.replace(new RegExp('(\\.[0-9]{' + iDecimalPlace + '})[0-9]*$'), '$1');
                } else {
                    value = value.replace(/[^(0-9|\-)]/g, '');
                }
                target.value = value;
            }
        }

        function replaceToPrice(target)
        {
            var value = target.value;

            var regExpTest = new RegExp('^[0-9]*' + (iDecimalPlace ? '' : '\\.[0-9]{0, ' + iDecimalPlace + '}' ) + '$');
            if (regExpTest.test(value) === false) {
                value = value.replace(/[^0-9.]/g, '');
                if (parseInt(iDecimalPlace)) {
                    value = value.replace(/^([0-9]+\.[0-9]+)\.+.*$/, '$1');
                    value = value.replace(new RegExp('(\\.[0-9]{' + iDecimalPlace + '})[0-9]*$'), '$1');
                } else {
                    value = value.replace(/\.+[0-9]*$/, '');
                }
                target.value = value;
            }
        }

        function attachEvent(elem, sEventName, fn)
        {
            if ( elem.addEventListener ) {
                elem.addEventListener( sEventName, fn, false );

            } else if ( elem.attachEvent ) {
                elem.attachEvent( "on" + sEventName, fn );
            }
        }

    }
};

if (window.jQuery !== undefined) {
    $.fn.extend({
        toShopPriceInput : function(iShopNo)
        {
            return this.each(function(){
                var iElementShopNo = $(this).data('shop_no') || iShopNo;
                SHOP_PRICE_UTIL.toShopPriceInput(this, iElementShopNo);
            });
        },
        toShopSubPriceInput : function(iShopNo)
        {
            return this.each(function(){
                var iElementShopNo = $(this).data('shop_no') || iShopNo;
                SHOP_PRICE_UTIL.toShopSubPriceInput(this, iElementShopNo);
            });
        },
        toBasePriceInput : function(iShopNo)
        {
            return this.each(function(){
                var iElementShopNo = $(this).data('shop_no') || iShopNo;
                SHOP_PRICE_UTIL.toBasePriceInput(this, iElementShopNo);
            });
        }
    });
}

// EC$ 별칭용
if (typeof window.EC$ === 'function') {
    EC$.fn.extend({
        toShopPriceInput : function(iShopNo)
        {
            return this.each(function(){
                var iElementShopNo = EC$(this).data('shop_no') || iShopNo;
                SHOP_PRICE_UTIL.toShopPriceInput(this, iElementShopNo);
            });
        },
        toShopSubPriceInput : function(iShopNo)
        {
            return this.each(function(){
                var iElementShopNo = EC$(this).data('shop_no') || iShopNo;
                SHOP_PRICE_UTIL.toShopSubPriceInput(this, iElementShopNo);
            });
        },
        toBasePriceInput : function(iShopNo)
        {
            return this.each(function(){
                var iElementShopNo = EC$(this).data('shop_no') || iShopNo;
                SHOP_PRICE_UTIL.toBasePriceInput(this, iElementShopNo);
            });
        }
    });
}

(function(window){
    window.htmlentities = {
        /**
         * Converts a string to its html characters completely.
         *
         * @param {String} str String with unescaped HTML characters
         **/
        encode : function(str) {
            var buf = [];

            for (var i=str.length-1; i>=0; i--) {
                buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
            }

            return buf.join('');
        },
        /**
         * Converts an html characterSet into its original character.
         *
         * @param {String} str htmlSet entities
         **/
        decode : function(str) {
            return str.replace(/&#(\d+);/g, function(match, dec) {
                return String.fromCharCode(dec);
            });
        }
    };
})(window);
/**
 * 비동기식 데이터
 */
var CAPP_ASYNC_METHODS = {
    DEBUG: false,
    IS_LOGIN: (document.cookie.match(/(?:^| |;)iscache=F/) ? true : false),
    EC_PATH_ROLE: EC$('meta[name="path_role"]').attr('content') || '',
    aDatasetList: [],
    $xansMyshopMain: EC$('.xans-myshop-main'),
    init : function()
    {
    	var bDebug = CAPP_ASYNC_METHODS.DEBUG;

        var aUseModules = [];
        var aNoCachedModules = [];

        EC$(CAPP_ASYNC_METHODS.aDatasetList).each(function(){
            var sKey = this;

            var oTarget = CAPP_ASYNC_METHODS[sKey];

            if (bDebug) {
                console.log(sKey);
            }
            var bIsUse = oTarget.isUse();
            if (bDebug) {
                console.log('   isUse() : ' + bIsUse);
            }

            if (bIsUse === true) {
                aUseModules.push(sKey);

                if (oTarget.restoreCache === undefined || oTarget.restoreCache() === false) {
                    if (bDebug) {
                        console.log('   restoreCache() : true');
                    }
                    aNoCachedModules.push(sKey);
                }
            }
        });

        if (aNoCachedModules.length > 0) {
            var sEditor = '';
            try {
                if (bEditor === true) {
                    // 에디터에서 접근했을 경우 임의의 상품 지정
                    sEditor = '&PREVIEW_SDE=1';
                }
            } catch(e) { }

            var sPathRole = '&path_role=' + CAPP_ASYNC_METHODS.EC_PATH_ROLE;

            EC$.ajax(
            {
                url : '/exec/front/manage/async?module=' + aNoCachedModules.join(',') + sEditor + sPathRole,
                dataType : 'json',
                success : function(aData)
                {
                	CAPP_ASYNC_METHODS.setData(aData, aUseModules);
                }
            });

        } else {
        	CAPP_ASYNC_METHODS.setData({}, aUseModules);

        }
    },
    setData : function(aData, aUseModules)
    {
        aData = aData || {};

        EC$(aUseModules).each(function(){
            var sKey = this;

            var oTarget = CAPP_ASYNC_METHODS[sKey];

            if (oTarget.setData !== undefined && aData.hasOwnProperty(sKey) === true) {
                oTarget.setData(aData[sKey]);
            }

            if (oTarget.execute !== undefined) {
                oTarget.execute();
            }
        });
    },

    _getCookie: function(sCookieName)
    {
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        return aCookieValue ? aCookieValue[1] : null;
    }
};
/**
 * 비동기식 데이터 - 회원 정보
 */
CAPP_ASYNC_METHODS.aDatasetList.push('member');
CAPP_ASYNC_METHODS.member = {
    __sEncryptedString: null,
    __isAdult: 'F',

    // 회원 데이터
    __sMemberId: null,
    __sName: null,
    __sNickName: null,
    __sGroupName: null,
    __sEmail: null,
    __sPhone: null,
    __sCellphone: null,
    __sBirthday: null,
    __sGroupNo: null,
    __sBoardWriteName: null,
    __sAdditionalInformation: null,
    __sCreatedDate: null,

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (EC$('.xans-layout-statelogon, .xans-layout-logon').length > 0) {
                return true;
            }

            if (CAPP_ASYNC_METHODS.recent.isUse() === true
                && typeof(EC_FRONT_JS_CONFIG_SHOP) !== 'undefined'
                && EC_FRONT_JS_CONFIG_SHOP.adult19Warning === 'T') {
                return true;
            }

            if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('customer', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
                return true;
            }

        } else {
            // 비 로그인 상태에서 삭제처리
            this.removeCache();
        }

        return false;
    },

    restoreCache: function()
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return false;
        }

        // 데이터 복구 유무
        var bRestored = false;

        try {
            // 데이터 복구
            var oCache = JSON.parse(window.sessionStorage.getItem('member_' + EC_SDE_SHOP_NUM));

            // expire 체크
            if (oCache.exp < Date.now()) {
                throw 'cache has expired.';
            }

            // 데이터 체크
            if (typeof oCache.data.member_id === 'undefined'
                || oCache.data.member_id === ''
                || typeof oCache.data.name === 'undefined'
                || typeof oCache.data.nick_name === 'undefined'
                || typeof oCache.data.group_name === 'undefined'
                || typeof oCache.data.group_no === 'undefined'
                || typeof oCache.data.email === 'undefined'
                || typeof oCache.data.phone === 'undefined'
                || typeof oCache.data.cellphone === 'undefined'
                || typeof oCache.data.birthday === 'undefined'
                || typeof oCache.data.board_write_name === 'undefined'
                || typeof oCache.data.additional_information === 'undefined'
                || typeof oCache.data.created_date === 'undefined'
            ) {
                throw 'Invalid cache data.';
            }

            // 데이터 복구
            this.__sMemberId = oCache.data.member_id;
            this.__sName = oCache.data.name;
            this.__sNickName = oCache.data.nick_name;
            this.__sGroupName = oCache.data.group_name;
            this.__sGroupNo   = oCache.data.group_no;
            this.__sEmail = oCache.data.email;
            this.__sPhone = oCache.data.phone;
            this.__sCellphone = oCache.data.cellphone;
            this.__sBirthday = oCache.data.birthday;
            this.__sBoardWriteName = oCache.data.board_write_name;
            this.__sAdditionalInformation = oCache.data.additional_information;
            this.__sCreatedDate = oCache.data.created_date;

            bRestored = true;
        } catch(e) {
            // 복구 실패시 캐시 삭제
            this.removeCache();
        }

        return bRestored;
    },

    cache: function()
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return;
        }

        // 캐시
        window.sessionStorage.setItem('member_' + EC_SDE_SHOP_NUM, JSON.stringify({
            exp: Date.now() + (1000 * 60 * 10),
            data: this.getData()
        }));
    },

    removeCache: function()
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return;
        }

        // 캐시 삭제
        window.sessionStorage.removeItem('member_' + EC_SDE_SHOP_NUM);
    },

    setData: function(oData)
    {
        this.__sEncryptedString = oData.memberData;
        this.__isAdult = oData.memberIsAdult;
    },

    execute: function()
    {
        if (this.__sMemberId === null) {
            AuthSSLManager.weave({
                'auth_mode'          : 'decryptClient',
                'auth_string'        : this.__sEncryptedString,
                'auth_callbackName'  : 'CAPP_ASYNC_METHODS.member.setDataCallback'
            });
        } else {
            this.render();
        }
    },

    setDataCallback: function(sData)
    {
        try {
            var sDecodedData = decodeURIComponent(sData);

            if (AuthSSLManager.isError(sDecodedData) == true) {
                console.log(sDecodedData);
                return;
            }

            var oData = AuthSSLManager.unserialize(sDecodedData);
            this.__sMemberId = oData.id || '';
            this.__sName = oData.name || '';
            this.__sNickName = oData.nick || '';
            this.__sGroupName = oData.group_name || '';
            this.__sGroupNo   = oData.group_no || '';
            this.__sEmail = oData.email || '';
            this.__sPhone = oData.phone || '';
            this.__sCellphone = oData.cellphone || '';
            this.__sBirthday = oData.birthday || 'F';
            this.__sBoardWriteName = oData.board_write_name || '';
            this.__sAdditionalInformation = oData.additional_information || '';
            this.__sCreatedDate = oData.created_date || '';

            // 데이터 랜더링
            this.render();

            // 데이터 캐시
            this.cache();
        } catch(e) {}
    },

    render: function()
    {
        // 친구초대
        if (EC$('.xans-myshop-asyncbenefit').length > 0) {
            EC$('#reco_url').attr({value: EC$('#reco_url').val() + this.__sMemberId});
        }

        EC$('.authssl_member_name').html(this.__sName);
        EC$('.xans-member-var-id').html(this.__sMemberId);
        EC$('.xans-member-var-name').html(this.__sName);
        EC$('.xans-member-var-nick').html(this.__sNickName);
        EC$('.xans-member-var-group_name').html(this.__sGroupName);
        EC$('.xans-member-var-group_no').html(this.__sGroupNo);
        EC$('.xans-member-var-email').html(this.__sEmail);
        EC$('.xans-member-var-phone').html(this.__sPhone);

        if (EC$('.xans-board-commentwrite').length > 0 && typeof BOARD_COMMENT !== 'undefined') {
            BOARD_COMMENT.setCmtData();
        }
    },

    getMemberIsAdult: function()
    {
        return this.__isAdult;
    },

    getData: function()
    {
        return {
            member_id: this.__sMemberId,
            name: this.__sName,
            nick_name: this.__sNickName,
            group_name: this.__sGroupName,
            group_no: this.__sGroupNo,
            email: this.__sEmail,
            phone: this.__sPhone,
            cellphone: this.__sCellphone,
            birthday: this.__sBirthday,
            board_write_name: this.__sBoardWriteName,
            additional_information: this.__sAdditionalInformation,
            created_date: this.__sCreatedDate
        };
    }
};
/**
 * 비동기식 데이터 - 예치금
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Ordercnt');
CAPP_ASYNC_METHODS.Ordercnt = {
    __iOrderShppiedBeforeCount: null,
    __iOrderShppiedStandbyCount: null,
    __iOrderShppiedBeginCount: null,
    __iOrderShppiedComplateCount: null,
    __iOrderShppiedCancelCount: null,
    __iOrderShppiedExchangeCount: null,
    __iOrderShppiedReturnCount: null,

    __$target: EC$('#xans_myshop_orderstate_shppied_before_count'),
    __$target2: EC$('#xans_myshop_orderstate_shppied_standby_count'),
    __$target3: EC$('#xans_myshop_orderstate_shppied_begin_count'),
    __$target4: EC$('#xans_myshop_orderstate_shppied_complate_count'),
    __$target5: EC$('#xans_myshop_orderstate_order_cancel_count'),
    __$target6: EC$('#xans_myshop_orderstate_order_exchange_count'),
    __$target7: EC$('#xans_myshop_orderstate_order_return_count'),

    isUse: function()
    {
        if (EC$('.xans-myshop-orderstate').length > 0) {
            return true; 
        }

        return false;
    },

    restoreCache: function()
    {
        var sCookieName = 'ordercnt_' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            var aData = EC_UTIL.parseJSON(decodeURIComponent(aCookieValue[1]));
            this.__iOrderShppiedBeforeCount = aData.shipped_before_count;
            this.__iOrderShppiedStandbyCount = aData.shipped_standby_count;
            this.__iOrderShppiedBeginCount = aData.shipped_begin_count;
            this.__iOrderShppiedComplateCount = aData.shipped_complate_count;
            this.__iOrderShppiedCancelCount = aData.order_cancel_count;
            this.__iOrderShppiedExchangeCount = aData.order_exchange_count;
            this.__iOrderShppiedReturnCount = aData.order_return_count;
            return true;
        }

        return false;
    },

    setData: function(aData)
    {
        this.__iOrderShppiedBeforeCount = aData['shipped_before_count'];
        this.__iOrderShppiedStandbyCount = aData['shipped_standby_count'];
        this.__iOrderShppiedBeginCount = aData['shipped_begin_count'];
        this.__iOrderShppiedComplateCount = aData['shipped_complate_count'];
        this.__iOrderShppiedCancelCount = aData['order_cancel_count'];
        this.__iOrderShppiedExchangeCount = aData['order_exchange_count'];
        this.__iOrderShppiedReturnCount = aData['order_return_count'];
    },

    execute: function()
    {
        this.__$target.html(this.__iOrderShppiedBeforeCount);
        this.__$target2.html(this.__iOrderShppiedStandbyCount);
        this.__$target3.html(this.__iOrderShppiedBeginCount);
        this.__$target4.html(this.__iOrderShppiedComplateCount);
        this.__$target5.html(this.__iOrderShppiedCancelCount);
        this.__$target6.html(this.__iOrderShppiedExchangeCount);
        this.__$target7.html(this.__iOrderShppiedReturnCount);
    },

    getData: function()
    {
        return {
            shipped_before_count: this.__iOrderShppiedBeforeCount,
            shipped_standby_count: this.__iOrderShppiedStandbyCount,
            shipped_begin_count: this.__iOrderShppiedBeginCount,
            shipped_complate_count: this.__iOrderShppiedComplateCount,
            order_cancel_count: this.__iOrderShppiedCancelCount,
            order_exchange_count: this.__iOrderShppiedExchangeCount,
            order_return_count: this.__iOrderShppiedReturnCount
        };
    }
};
/**
 * 비동기식 데이터 - 장바구니 갯수
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Basketcnt');
CAPP_ASYNC_METHODS.Basketcnt = {
    __iBasketCount: null,

    __$target: EC$('.xans-layout-orderbasketcount span a'),
    __$target2: EC$('#xans_myshop_basket_cnt'),
    __$target3: CAPP_ASYNC_METHODS.$xansMyshopMain.find('.xans_myshop_main_basket_cnt'),
    __$target4: EC$('.EC-Layout-Basket-count'),

    isUse: function()
    {
        if (this.__$target.length > 0) {
            return true;
        }
        if (this.__$target2.length > 0) {
            return true;
        }
        if (this.__$target3.length > 0) {
            return true;
        }
        if (this.__$target4.length > 0) {
            return true;
        }

        if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('personal', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
            return true;
        }

        return false;
    },

    restoreCache: function()
    {
        var sCookieName = 'basketcount_' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            this.__iBasketCount = parseInt(aCookieValue[1], 10);
            return true;
        }
        
        return false;
    },

    setData: function(sData)
    {
        this.__iBasketCount = Number(sData);
    },

    execute: function()
    {
        this.__$target.html(this.__iBasketCount);

        if (SHOP.getLanguage() === 'ko_KR') {
            this.__$target2.html(this.__iBasketCount + '개');
        } else {
            this.__$target2.html(this.__iBasketCount);
        }

        this.__$target3.html(this.__iBasketCount);
        
        this.__$target4.html(this.__iBasketCount);
        
        if (this.__iBasketCount > 0 && this.__$target4.length > 0) {
            var $oCountDisplay = EC$('.EC-Layout_Basket-count-display');

            if ($oCountDisplay.length > 0) {
                $oCountDisplay.removeClass('displaynone');
            }
        }
    },

    getData: function()
    {
        return {
            count: this.__iBasketCount
        };
    }
};
/**
 * 비동기식 데이터 - 장바구니 금액
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Basketprice');
CAPP_ASYNC_METHODS.Basketprice = {
    __sBasketPrice: null,

    __$target: EC$('#xans_myshop_basket_price'),

    isUse: function()
    {
        if (this.__$target.length > 0) {
            return true;
        }

        if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('personal', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
            return true;
        }

        return false;
    },

    restoreCache: function()
    {
        var sCookieName = 'basketprice_' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            this.__sBasketPrice = decodeURIComponent((aCookieValue[1]+ '').replace(/\+/g, '%20'));
            return true;
        }
        
        return false;
    },

    setData: function(sData)
    {
        this.__sBasketPrice = sData;
    },

    execute: function()
    {
        this.__$target.html(this.__sBasketPrice);
    },

    getData: function()
    {
        // 데이터 없는경우 0
        var sBasketPrice = (this.__sBasketPrice || 0) + '';

        return {
            basket_price: parseFloat(SHOP_PRICE_FORMAT.detachFormat(htmlentities.decode(sBasketPrice))).toFixed(2)
        };
    }
};
/*
 * 비동기식 데이터 - 장바구니 상품리스트
 */
CAPP_ASYNC_METHODS.aDatasetList.push('BasketProduct');
CAPP_ASYNC_METHODS.BasketProduct = {

    STORAGE_KEY: 'BasketProduct_' +  EC_SDE_SHOP_NUM,

    __aData: null,

    __$target: EC$('.xans-layout-orderbasketcount span a'),
    __$target2: EC$('#xans_myshop_basket_cnt'),
    __$target3: CAPP_ASYNC_METHODS.$xansMyshopMain.find('.xans_myshop_main_basket_cnt'),
    __$target4: EC$('.EC-Layout-Basket-count'),

    isUse: function()
    {
        if (this.__$target.length > 0) {
            return true;
        }
        if (this.__$target2.length > 0) {
            return true;
        }
        if (this.__$target3.length > 0) {
            return true;
        }
        if (this.__$target4.length > 0) {
            return true;
        }

        if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('personal', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
            return true;
        }
    },

    restoreCache: function()
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return false;
        }

        var sSessionStorageData = window.sessionStorage.getItem(this.STORAGE_KEY);
        if (sSessionStorageData === null) {
            return false;
        }

        try {
            this.__aData = [];
            var aStorageData = JSON.parse(sSessionStorageData);

            for (var iKey in aStorageData) {
                this.__aData.push(aStorageData[iKey]);
            }

            return true;
        } catch(e) {

            // 복구 실패시 캐시 삭제
            this.removeCache();

            return false;
        }
    },

    removeCache: function()
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return;
        }
        // 캐시 삭제
        window.sessionStorage.removeItem(this.STORAGE_KEY);
    },

    setData: function(oData)
    {
        this.__aData = oData;

        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return;
        }

        try {
            sessionStorage.setItem(this.STORAGE_KEY, JSON.stringify(this.getData()));
        } catch (error) {
        }
    },

    execute: function()
    {

    },

    getData: function()
    {
        var aStorageData = this.__aData;

        if (aStorageData != null) {
            var oNewStorageData = [];

            for (var iKey in aStorageData) {
                oNewStorageData.push(aStorageData[iKey]);
            }

            return oNewStorageData;
        } else {
            return false;
        }
    }
};
/**
 * 비동기식 데이터 - 쿠폰 갯수
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Couponcnt');
CAPP_ASYNC_METHODS.Couponcnt = {
    __iCouponCount: null,

    __$target: EC$('.xans-layout-myshopcouponcount'),
    __$target2: EC$('#xans_myshop_coupon_cnt'),
    __$target3: CAPP_ASYNC_METHODS.$xansMyshopMain.find('.xans_myshop_main_coupon_cnt'),
    __$target4: EC$('#xans_myshop_bankbook_coupon_cnt'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (this.__$target.length > 0) {
                return true;
            }

            if (this.__$target2.length > 0) {
                return true;
            }

            if (this.__$target3.length > 0) {
                return true;
            }

            if (this.__$target4.length > 0) {
                return true;
            }

            if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('promotion', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
                return true;
            }
        }

        return false;
    },
    
    restoreCache: function()
    {
        var sCookieName = 'couponcount_' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            this.__iCouponCount = parseInt(aCookieValue[1], 10);
            return true;
        }
        
        return false;
    },
    setData: function(sData)
    {
        this.__iCouponCount = Number(sData);
    },

    execute: function()
    {
        this.__$target.html(this.__iCouponCount);

        if (SHOP.getLanguage() === 'ko_KR') {
            this.__$target2.html(this.__iCouponCount + '개');
        } else {
            this.__$target2.html(this.__iCouponCount);
        }

        this.__$target3.html(this.__iCouponCount);
        this.__$target4.html(this.__iCouponCount);
    },

    getData: function()
    {
        return {
            count: this.__iCouponCount
        };
    }
};
/**
 * 비동기식 데이터 - 적립금
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Mileage');
CAPP_ASYNC_METHODS.Mileage = {
    __sAvailMileage: null,
    __sUsedMileage: null,
    __sTotalMileage: null,
    __sUnavailMileage: null,
    __sReturnedMileage: null,

    __$target: EC$('#xans_myshop_mileage'),
    __$target2: EC$('#xans_myshop_bankbook_avail_mileage, #xans_myshop_summary_avail_mileage'),
    __$target3: EC$('#xans_myshop_bankbook_used_mileage, #xans_myshop_summary_used_mileage'),
    __$target4: EC$('#xans_myshop_bankbook_total_mileage, #xans_myshop_summary_total_mileage'),
    __$target5: EC$('#xans_myshop_summary_unavail_mileage'),
    __$target6: EC$('#xans_myshop_summary_returned_mileage'),
    __$target7: EC$('#xans_myshop_avail_mileage'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (this.__$target.length > 0) {
                return true;
            }

            if (this.__$target2.length > 0) {
                return true;
            }

            if (this.__$target3.length > 0) {
                return true;
            }

            if (this.__$target4.length > 0) {
                return true;
            }

            if (this.__$target5.length > 0) {
                return true;
            }

            if (this.__$target6.length > 0) {
                return true;
            }

            if (this.__$target7.length > 0) {
                return true;
            }

            if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('customer', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
                return true;
            }
        }

        return false;
    },

    restoreCache: function()
    {
        // 특정 경로 룰의 경우 복구 취소
        if (PathRoleValidator.isInvalidPathRole()) {
            return false;
        }

        // 쿠키로부터 데이터 획득
        var sAvailMileage = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_avail_mileage_' + EC_SDE_SHOP_NUM);
        var sReturnedMileage = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_returned_mileage_' + EC_SDE_SHOP_NUM);
        var sUnavailMileage = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_unavail_mileage_' + EC_SDE_SHOP_NUM);
        var sUsedMileage = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_used_mileage_' + EC_SDE_SHOP_NUM);

        // 데이터가 하나라도 없는경우 복구 실패
        if (sAvailMileage === null
            || sReturnedMileage === null
            || sUnavailMileage === null
            || sUsedMileage === null
        ) {
            return false;
        }

        // 전체 마일리지 계산
        var sTotalMileage = (parseFloat(sAvailMileage) +
            parseFloat(sUnavailMileage) +
            parseFloat(sUsedMileage)).toString();

        // 단위정보를 계산하여 필드에 셋
        this.__sAvailMileage = parseFloat(sAvailMileage).toFixed(2);
        this.__sReturnedMileage = parseFloat(sReturnedMileage).toFixed(2);
        this.__sUnavailMileage = parseFloat(sUnavailMileage).toFixed(2);
        this.__sUsedMileage = parseFloat(sUsedMileage).toFixed(2);
        this.__sTotalMileage = parseFloat(sTotalMileage).toFixed(2);

        return true;
    },

    setData: function(aData)
    {
        this.__sAvailMileage = parseFloat(aData['avail_mileage'] || 0).toFixed(2);
        this.__sUsedMileage = parseFloat(aData['used_mileage'] || 0).toFixed(2);
        this.__sTotalMileage = parseFloat(aData['total_mileage'] || 0).toFixed(2);
        this.__sUnavailMileage = parseFloat(aData['unavail_mileage'] || 0).toFixed(2);
        this.__sReturnedMileage = parseFloat(aData['returned_mileage'] || 0).toFixed(2);
    },

    execute: function()
    {
        this.__$target.html(SHOP_PRICE_FORMAT.toShopMileagePrice(this.__sAvailMileage));
        this.__$target2.html(SHOP_PRICE_FORMAT.toShopMileagePrice(this.__sAvailMileage));
        this.__$target3.html(SHOP_PRICE_FORMAT.toShopMileagePrice(this.__sUsedMileage));
        this.__$target4.html(SHOP_PRICE_FORMAT.toShopMileagePrice(this.__sTotalMileage));
        this.__$target5.html(SHOP_PRICE_FORMAT.toShopMileagePrice(this.__sUnavailMileage));
        this.__$target6.html(SHOP_PRICE_FORMAT.toShopMileagePrice(this.__sReturnedMileage));
        this.__$target7.html(SHOP_PRICE_FORMAT.toShopMileagePrice(this.__sAvailMileage));
    },

    getData: function()
    {
        return {
            available_mileage: this.__sAvailMileage,
            used_mileage: this.__sUsedMileage,
            total_mileage: this.__sTotalMileage,
            returned_mileage: this.__sReturnedMileage,
            unavailable_mileage: this.__sUnavailMileage
        };
    }
};
/**
 * 비동기식 데이터 - 예치금
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Deposit');
CAPP_ASYNC_METHODS.Deposit = {
    __sTotalDeposit: null,
    __sAllDeposit: null,
    __sUsedDeposit: null,
    __sRefundWaitDeposit: null,
    __sMemberTotalDeposit: null,

    __$target: EC$('#xans_myshop_deposit'),
    __$target2: EC$('#xans_myshop_bankbook_deposit'),
    __$target3: EC$('#xans_myshop_summary_deposit'),
    __$target4: EC$('#xans_myshop_summary_all_deposit'),
    __$target5: EC$('#xans_myshop_summary_used_deposit'),
    __$target6: EC$('#xans_myshop_summary_refund_wait_deposit'),
    __$target7: EC$('#xans_myshop_total_deposit'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (this.__$target.length > 0) {
                return true;
            }

            if (this.__$target2.length > 0) {
                return true;
            }

            if (this.__$target3.length > 0) {
                return true;
            }

            if (this.__$target4.length > 0) {
                return true;
            }

            if (this.__$target5.length > 0) {
                return true;
            }

            if (this.__$target6.length > 0) {
                return true;
            }

            if (this.__$target7.length > 0) {
                return true;
            }

            if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('customer', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
                return true;
            }
        }

        return false;
    },

    restoreCache: function()
    {
        // 특정 경로 룰의 경우 복구 취소
        if (PathRoleValidator.isInvalidPathRole()) {
            return false;
        }

        // 쿠키로부터 데이터 획득
        var sAllDeposit = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_all_deposit_' + EC_SDE_SHOP_NUM);
        var sUsedDeposit = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_used_deposit_' + EC_SDE_SHOP_NUM);
        var sRefundWaitDeposit = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_deposit_refund_wait_' + EC_SDE_SHOP_NUM);
        var sMemberTotalDeposit = CAPP_ASYNC_METHODS._getCookie('ec_async_cache_member_total_deposit_' + EC_SDE_SHOP_NUM);

        // 데이터가 하나라도 없는경우 복구 실패
        if (sAllDeposit === null
            || sUsedDeposit === null
            || sRefundWaitDeposit === null
            || sMemberTotalDeposit === null
        ) {
            return false;
        }

        // 사용 가능한 예치금 계산
        var sTotalDeposit = (parseFloat(sAllDeposit) -
            parseFloat(sUsedDeposit) -
            parseFloat(sRefundWaitDeposit)).toString();

        // 단위정보를 계산하여 필드에 셋
        this.__sTotalDeposit = parseFloat(sTotalDeposit).toFixed(2);
        this.__sAllDeposit = parseFloat(sAllDeposit).toFixed(2);
        this.__sUsedDeposit = parseFloat(sUsedDeposit).toFixed(2);
        this.__sRefundWaitDeposit = parseFloat(sRefundWaitDeposit).toFixed(2);
        this.__sMemberTotalDeposit = parseFloat(sMemberTotalDeposit).toFixed(2);

        return true;
    },

    setData: function(aData)
    {
        this.__sTotalDeposit = parseFloat(aData['total_deposit'] || 0).toFixed(2);
        this.__sAllDeposit = parseFloat(aData['all_deposit'] || 0).toFixed(2);
        this.__sUsedDeposit = parseFloat(aData['used_deposit'] || 0).toFixed(2);
        this.__sRefundWaitDeposit = parseFloat(aData['deposit_refund_wait'] || 0).toFixed(2);
        this.__sMemberTotalDeposit = parseFloat(aData['member_total_deposit'] || 0).toFixed(2);
    },

    execute: function()
    {
        this.__$target.html(SHOP_PRICE_FORMAT.toShopDepositPrice(this.__sTotalDeposit));
        this.__$target2.html(SHOP_PRICE_FORMAT.toShopDepositPrice(this.__sTotalDeposit));
        this.__$target3.html(SHOP_PRICE_FORMAT.toShopDepositPrice(this.__sTotalDeposit));
        this.__$target4.html(SHOP_PRICE_FORMAT.toShopDepositPrice(this.__sAllDeposit));
        this.__$target5.html(SHOP_PRICE_FORMAT.toShopDepositPrice(this.__sUsedDeposit));
        this.__$target6.html(SHOP_PRICE_FORMAT.toShopDepositPrice(this.__sRefundWaitDeposit));
        this.__$target7.html(SHOP_PRICE_FORMAT.toShopDepositPrice(this.__sMemberTotalDeposit));
    },

    getData: function()
    {
        return {
            total_deposit: this.__sTotalDeposit,
            used_deposit: this.__sUsedDeposit,
            refund_wait_deposit: this.__sRefundWaitDeposit,
            all_deposit: this.__sAllDeposit,
            member_total_deposit: this.__sMemberTotalDeposit
        };
    }
};
/**
 * 비동기식 데이터 - 위시리스트
 */
CAPP_ASYNC_METHODS.aDatasetList.push('WishList');
CAPP_ASYNC_METHODS.WishList = {
    STORAGE_KEY: 'localWishList' +  EC_SDE_SHOP_NUM,
    __$targetWishIcon: EC$('.icon_img.ec-product-listwishicon'),
    __$targetWishList: EC$('.xans-myshop-wishlist'),
    __aWishList: null,
    __aTags_on: null,
    __aTags_off: null,

    isUse: function()
    {
        if (this.__$targetWishIcon.length > 0 || this.__$targetWishList.length > 0
        || CAPP_ASYNC_METHODS.EC_PATH_ROLE === 'PRODUCT_DETAIL') {
            return true;
        }
        return false;
    },

    restoreCache: function()
    {
        if (!window.sessionStorage) {
            return false;
        }

        var sSessionStorageData = window.sessionStorage.getItem(this.STORAGE_KEY);
        if (sSessionStorageData === null) {
            return false;
        }

        var aStorageData = EC_UTIL.parseJSON(sSessionStorageData);
        if (this.__$targetWishList.length > 0 || aStorageData['isLogin'] !== CAPP_ASYNC_METHODS.IS_LOGIN) {
            this.clearStorage();
            return false;
        }

        var aWishList = aStorageData['wishList'];
        this.__aTags_on = aStorageData['on_tags'];
        this.__aTags_off = aStorageData['off_tags'];
        this.__aWishList = [];
        for (var i = 0; i < aWishList.length; i++) {
            var aTempWishList = [];
            aTempWishList.product_no = aWishList[i];
            this.__aWishList.push(aTempWishList);
        }
        return true;
    },

    setData: function(aData)
    {
        if (aData.hasOwnProperty('wishList') === false || aData.hasOwnProperty('on_tags') === false) {
            return;
        }

        this.__aWishList = aData.wishList;
        this.__aTags_on = aData.on_tags;
        this.__aTags_off = aData.off_tags;

        if (window.sessionStorage) {
            var aWishList = [];

            for (var i = 0; i < aData.wishList.length; i++) {
                aWishList.push(aData.wishList[i].product_no);
            }

            var oNewStorageData = {
                'wishList' : aWishList,
                'on_tags' : aData.on_tags,
                'off_tags' : aData.off_tags,
                'isLogin' : CAPP_ASYNC_METHODS.IS_LOGIN
            };

            if (typeof oNewStorageData !== 'undefined') {
                sessionStorage.setItem(this.STORAGE_KEY , JSON.stringify(oNewStorageData));
            }
        }
    },

    execute: function()
    {
        var aWishList = this.__aWishList;
        var aTagsOn = this.__aTags_on;
        var aTagsOff = this.__aTags_off;

        if (aWishList === null || typeof aWishList === 'undefined') {
            aWishList = [];
        }

        var oTarget = EC$('.ec-product-listwishicon');
        for (var sKey in aTagsOff) {
            oTarget.attr(sKey, aTagsOff[sKey]);
        }

        for (var i = 0; i < aWishList.length; i++) {
            assignAttribute(aWishList[i]);
        }

        /**
         * oTarget 엘레먼트에 aData의 정보를 어싸인함.
         * @param array aData 위시리스트 정보
         */
        function assignAttribute(aData)
        {
            var iProductNo = aData['product_no'];
            var oTarget = EC$('.ec-product-listwishicon[productno="'+iProductNo+'"]');

            // oTarget의 src, alt, icon_status attribute의 값을 할당
            for (var sKey in aTagsOn) {
                oTarget.attr(sKey, aTagsOn[sKey]);
            }
        }

    },

    /**
     * 세션스토리지 삭제
     */
    clearStorage: function()
    {
        if (!window.sessionStorage) {
            return;
        }
        window.sessionStorage.removeItem(this.STORAGE_KEY);
    },

    /**
     * sCommand에 따른 sessionStorage Set
     * @param iProductNo
     * @param sCommand 추가(add)/삭제(del) sCommand
     */
    setSessionStorageItem: function(iProductNo, sCommand)
    {
        if (this.isUse() === false) {
            return;
        }

        var oStorageData = EC_UTIL.parseJSON(sessionStorage.getItem(this.STORAGE_KEY));
        var aWishList = oStorageData['wishList'];
        var iLimit = 200;

        if (aWishList === null) {
            aWishList = [];
        }

        var iProductNo = parseInt(iProductNo, 10);
        var iIndex = aWishList.indexOf(iProductNo);

        if (sCommand === 'add') {
            if (aWishList.length >= iLimit) {
                aWishList.splice(aWishList.length - 1, 1);
            }
            if (iIndex < 0) {
                aWishList.unshift(iProductNo);
            }
        } else {
            if (iIndex > -1) {
                aWishList.splice(iIndex, 1);
            }
        }

        oStorageData['wishList'] = aWishList;
        sessionStorage.setItem(this.STORAGE_KEY, JSON.stringify(oStorageData));
    }
};

