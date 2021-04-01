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

/**
 * front - 쿠폰관련 js집합
 *
 * @package app/Newcoupon
 * @subpackage Front
 * @author 백병한,신호섭 <bhbaek@simplexi.com>
 * @since 2013. 05. 06.
 * @version 1.0
 * */

/**
 * 시리얼 쿠폰 등록폼의 엔터키 동작 처리추가
 */
EC$('#frmSerialCoupon').on("keypress", function(e) {
    if (e.keyCode == 13) {
        return coupon_code_check();
    }
});

EC$('#frmCouponlist').on("keypress", function(e) {
    if (e.keyCode == 13) {
        return COUPON.useCoupon();
    }
});



function coupon_code_submit()
{
    if (coupon_code_check() === true) coupon_submit('frmSerialCoupon');
}

/**
 * 시리얼 쿠폰 체크
 * @returns {Boolean}
 */
function coupon_code_check()
{
    if (COUPON.is_coupon_code_submit === true) {
        setTimeout(alert(__('TRY.FEW.MINUTES', 'NEWCOUPON.FRONT.COUPON.JS')), 3000);
        location.reload();
        return;
    }

    if (EC$('#frmSerialCoupon #coupon_code').val().length > 35 || EC$('#frmSerialCoupon #coupon_code').val().length < 10) {

        if (EC$('#frmSerialCoupon #coupon_code').val().length == 0) {
            alert(__('쿠폰번호를 입력해주세요'));
        } else {
            alert(__('쿠폰번호는 10~35자리 입니다.'));
        }

        EC$('#frmSerialCoupon #coupon_code').focus();

        return false;
    }

    COUPON.is_coupon_code_submit = true;
    return true;

}

function coupon_product_popup(url, coupon_no)
{
    window.open(url+'?coupon_no='+coupon_no, 'myshop_coupon_pop_list','width=700,height=630,scrollbars=yes,resizable=yes,menubar=no,toolbar=no,location=no,status=no');

}

function coupon_submit(sFormName)
{
    EC$('#'+sFormName).submit();
}


function listsize_change(sFormName)
{
    var $f = EC$("#" + sFormName);
    if (EC$("#limit").length == 0) {
        $f.append("<input type='hidden' id='limit' name='limit'>");
    }
    EC$("#limit").val(EC$("#list_size option:selected").val());
    $f.submit();
}

function Layer_overload_pop(LayerName,Status)
{
    try
    {
        var LayerN;

        if (navigator.appName == "Netscape")
        {
            LayerN = document.getElementById(LayerName).style;
            if (Status == 'show') LayerN.visibility = 'visible';
            if (Status == 'hide') LayerN.visibility = 'hidden';
        }
        else
        {
            LayerN = document.all[LayerName].style;
            if (Status == 'show') LayerN.visibility = 'visible';
            if (Status == 'hide') LayerN.visibility = 'hidden';
        }
    }
    catch (e)
    {
    }
}


var COUPON = {
    is_coupon_code_submit : false,
    is_coupon_use_submit : false,

    viewInfo:function(iCouponNo, oCouponElem)
    {
        var aPos = EC$(oCouponElem).offset();

        var oCoupon = aCouponInfo[iCouponNo];

        EC$('#dCouponDetail').remove();

        var sHtml = '<div id="dCouponDetail" class="layerTheme"></div>';

        EC$('body').append(sHtml);
        if (mobileWeb === true) {
            try {
                EC$('#dCouponDetail').html('<h4><strong>' + __('쿠폰정보') + '</strong></h4>' +
                        '<ul class="couponInfo">' +
                            '<li>' + __('쿠폰명') + ' : '   + decodeURIComponent(oCoupon.coupon_name) + '</li>' +
                            '<li>' + __('적용상품') + ' : ' + decodeURIComponent(oCoupon.coupon_product_info) + '</li>' +
                            '<li>' + __('사용조건') + ' : ' + decodeURIComponent(oCoupon.coupon_usecon) +  ' ' + decodeURIComponent(oCoupon.region_delivery_msg) + ' ' + decodeURIComponent(oCoupon.foreign_delivery_msg) + '</li>' +
                            '<li>' + __('발행수량') + ' : ' + decodeURIComponent(oCoupon.coupon_issue) + '</li>' +
                            '<li>' + __('사용기간') + ' : ' + decodeURIComponent(oCoupon.coupon_period_detail) + '</li>' +
                        '</ul>' +
                        '<p class="mButton">' +
                            '<a href="' + oCoupon.coupon_issue_url + '" class="tSubmit1">' + '<span>' + __('다운받기') + '</span></a>' +
                            '<a href="#none" class="tSubmit2" onclick="EC$(\'#dCouponDetail\').remove();">' + '<span>' + __('닫기') + '</span> </a>' +
                        '</p>');
            } catch (err) {
                EC$('#dCouponDetail').html('<h4><strong>' + __('쿠폰정보') + '</strong></h4>' +
                        '<ul class="couponInfo">' +
                            '<li>' + __('쿠폰명') + ' : ' + oCoupon.coupon_name + '</li>' +
                            '<li>' + __('적용상품') + ' : ' + oCoupon.coupon_product_info + '</li>' +
                            '<li>' + __('사용조건') + ' : ' + oCoupon.coupon_usecon +  ' ' + oCoupon.region_delivery_msg + ' ' + oCoupon.foreign_delivery_msg + '</li>' +
                            '<li>' + __('발행수량') + ' : ' + oCoupon.coupon_issue + '</li>' +
                            '<li>' + __('사용기간') + ' : ' + oCoupon.coupon_period_detail + '</li>' +
                        '</ul>' +
                        '<p class="mButton">' +
                            '<a href="' + oCoupon.coupon_issue_url + '" class="tSubmit1">' + '<span>' + __('다운받기') + '</span></a>' +
                            '<a href="#none" class="tSubmit2" onclick="EC$(\'#dCouponDetail\').remove();">' + '<span>' + __('닫기') + '</span> </a>' +
                        '</p>');
            }

            //EC$('#dCouponDetail').offset({top:aPos.top,left:aPos.left-10});
            var iLeft = '-' + EC$('#dCouponDetail').width() / 2 + 'px', iTop = '-' + EC$('#dCouponDetail').height() / 2 + 'px';
            EC$('#dCouponDetail').css({top: '50%', left: '50%', position:'fixed', marginTop: iTop,  marginLeft: iLeft});

        } else {
            try {
                EC$('#dCouponDetail').html('<h3 class="title">' + __('쿠폰정보') + '</h3>' +
                        '<a href="#none" onclick="EC$(\'#dCouponDetail\').remove();">' +
                            '<img src="//img.cafe24.com/images/ec_hosting/front/btn_close_003.gif" />' +
                        '</a>' +
                        '<div class="content">' +
                            '<ul>' +
                                '<li><span>' + __('쿠폰명') + ' :</span> '   + decodeURIComponent(oCoupon.coupon_name) + '</li>' +
                                '<li><span>' + __('적용상품') + ' :</span> ' + decodeURIComponent(oCoupon.coupon_product_info) + '</li>' +
                                '<li><span>' + __('사용조건') + ' :</span> ' + decodeURIComponent(oCoupon.coupon_usecon) +  ' ' + decodeURIComponent(oCoupon.region_delivery_msg) + ' ' + decodeURIComponent(oCoupon.foreign_delivery_msg) + '</li>' +
                                '<li><span>' + __('발행수량') + ' :</span> ' + decodeURIComponent(oCoupon.coupon_issue) + '</li>' +
                                '<li><span>' + __('사용기간') + ' :</span> ' + decodeURIComponent(oCoupon.coupon_period_detail) + '</li>' +
                            '</ul>' +
                        '<a href="' + oCoupon.coupon_issue_url + '&is_popup=' + '&opener_url=' + document.URL + '">' +
                            '<img src="//img.echosting.cafe24.com/skin/admin_' + SHOP.getLanguage() + '/product/btn_coupon_download.gif" />' +
                        '</a>' +
                        '</div>');
            } catch (err) {
                EC$('#dCouponDetail').html('<h3 class="title">' + __('쿠폰정보') + '</h3>' +
                        '<a href="#none" onclick="EC$(\'#dCouponDetail\').remove();">' +
                            '<img src="//img.cafe24.com/images/ec_hosting/front/btn_close_003.gif" />' +
                        '</a>' +
                        '<div class="content">' +
                            '<ul>' +
                                '<li><span>' + __('쿠폰명') + ' :</span> '   + oCoupon.coupon_name + '</li>' +
                                '<li><span>' + __('적용상품') + ' :</span> ' + oCoupon.coupon_product_info + '</li>' +
                                '<li><span>' + __('사용조건') + ' :</span> ' + oCoupon.coupon_useco +  ' ' + oCoupon.region_delivery_msg + ' ' + oCoupon.foreign_delivery_msg + '</li>' +
                                '<li><span>' + __('발행수량') + ' :</span> ' + oCoupon.coupon_issue + '</li>' +
                                '<li><span>' + __('사용기간') + ' :</span> ' + oCoupon.coupon_period_detail + '</li>' +
                            '</ul>' +
                            '<a href="' + oCoupon.coupon_issue_url + '&is_popup=' + '&opener_url=' + document.URL + '">' +
                                '<img src="//img.echosting.cafe24.com/skin/admin_' + SHOP.getLanguage() + '/product/btn_coupon_download.gif" />' +
                                '</a>' +
                        '</div>');
            }

            EC$('#dCouponDetail').offset({top:aPos.top+20,left:aPos.left+30});
        }

    },

    useCoupon:function()
    {
        if (COUPON.is_coupon_use_submit === true) {
            setTimeout(alert(__('USE.TRY.IN.FEW.MINUTES', 'NEWCOUPON.FRONT.COUPON.JS')), 3000);
            location.reload();
            return;
        }

        var cnt = 0;

        for (var i=0; i<document.frmCouponlist.length; i++ ) {

            if (( document.frmCouponlist.elements[i].type == "checkbox" ) && ( document.frmCouponlist.elements[i].name == "coupon_code[]" ) ) {
                if ( document.frmCouponlist.elements[i].checked == true )
                    cnt = cnt + 1;
            }
        }

        if ( cnt < 1 ) {
            alert(__("선택된 쿠폰이 없습니다."));
            return false;
        }

        COUPON.is_coupon_use_submit = true;
        document.frmCouponlist.action = '/exec/front/Coupon/Mileage/';
        document.frmCouponlist.submit();

    },

    //쿠폰발급결과 출력
    getDownCouponResultForm:function(data)
    {
        //var total = Object.keys(aCouponInfo).length;
        var total = data['total_list'];

        var sContent = "<ul>";
        if (typeof(data.message) === 'object') {
            EC$.each( data.message, function( key, val ) {
                sContent += '<li>'+val+'</li>';
            });
        } else {
            sContent = '<li>'+data.message+'</li>';
        }
        sContent += '</ul>';

        var iSuccessCnt = 0;
        if (typeof(data['issue_list']) !== 'undefined') {
            iSuccessCnt = data['issue_list'].length;
        }

        var aData = {
            'total' : total,
            'success_cnt'  : iSuccessCnt,
            'content'  : sContent
        };

        EC$.get(sCouponDownResultUrl, aData, function( formData ) {
            formData =  formData.replace(/\{\$total\}/, parseInt(total))
                .replace(/\{\$success_cnt\}/, parseInt(iSuccessCnt))
                .replace(/\{\$fail_cnt\}/, parseInt(total - iSuccessCnt))
                .replace(/\{\$content\}/, sContent);

            //EC$(".xans-coupon-productdetail").first().prepend(formData);
            EC$("body").prepend(formData);
        });
    }
};

/**
 * 상품상세 쿠폰 영역
 */
var EC_SHOP_FRONT_PRODUCT_INFO_COUPON =  {

    sCouponCacheKey : '',
    iExpireTime : 1000 * 60, //1분캐시

    /**
     * 상품상세 로딩시 호출
     * @param iProductNo
     * @param iCategoryNo
     */
    getPrdDetailCouponAjax : function (iProductNo, iCategoryNo) {
        var sPath = document.location.pathname;

        // ECHOSTING-317844 대응
        if (parseInt(iProductNo) < 1 || EC_UTIL.trim(parent.EC$('.ec-product-coupon').html()) != "") {
            return;
        }

        //세션 스토리지 사용 안함이면..
        if (!window.sessionStorage) {
            EC$.get(EC_FRONT_JS_CONFIG_SHOP.sCouponDownloadPage,{'product_no' : iProductNo,'cate_no' : iCategoryNo, 'sPath' : sPath, 'asyncEmbededPage' : 'T'} ,function(sHtml) {
                sHtml = sHtml.replace(/<script.*?ind-script\/(optimizer.php|i18n.php|moment.php).*?<\/script>/g, '');
                sHtml = sHtml.replace(/<script.*?\/cid.generate.js.*?<\/script>/g, '');
                EC_SHOP_FRONT_PRODUCT_INFO_COUPON.displayCouponDownload(sHtml, 'F');
            });
        } else {
            if (this.getCouponCache(iProductNo) == false) {
                EC$.get(EC_FRONT_JS_CONFIG_SHOP.sCouponDownloadPage,{'product_no' : iProductNo,'cate_no' : iCategoryNo, 'sPath' : sPath, 'asyncEmbededPage' : 'T'} ,function(sHtml) {
                    sHtml = sHtml.replace(/<script.*?ind-script\/(optimizer.php|i18n.php|moment.php).*?<\/script>/g, '');
                    sHtml = sHtml.replace(/<script.*?\/cid.generate.js.*?<\/script>/g, '');
                    EC_SHOP_FRONT_PRODUCT_INFO_COUPON.setCouponcache(sHtml);
                });
            }

        }
    },

    /**
     * view단
     * @param sHtml
     */
    displayCouponDownload : function(sHtml, bSessionStorageFlag) {
        document.getElementsByClassName('ec-product-coupon')[0].innerHTML = sHtml;

        if (bSessionStorageFlag =='F') { //쿠폰 세션스토리지 사용시에 /layout/basic/js/common.js 토글이벤트가 적용된다.
            EC$('div.xans-coupon-productdetailajax .title').click(function (e) {
                var toggle = EC$(this).parent('.eToggle');
                if (toggle.hasClass('disable') == false) {
                    EC$(this).parent('.eToggle').toggleClass('selected');
                }
            });
        }
    },

    /**
     * 쿠폰 html 캐시 처리
     * @param sHtml
     */
    setCouponcache : function(sHtml)
    {
        window.sessionStorage.setItem(this.sCouponCacheKey, JSON.stringify({
            exp: Date.now() + this.iExpireTime,
            data: {sHtml:sHtml}
        }));
        this.displayCouponDownload(sHtml, 'F');
    },

    /**
     * 쿠폰 html 캐시 가져오기
     * @returns {boolean}
     */
    getCouponCache : function(iProductNo)
    {
        this.sCouponCacheKey = 'coupon_download_' + iProductNo + '_' +  EC_SDE_SHOP_NUM;
        try {
            // 데이터 복구
            var oCache = JSON.parse(window.sessionStorage.getItem(this.sCouponCacheKey));
            // expire 체크
            if (oCache.exp < Date.now()) {
                throw 'cache has expired.';
            }
            // 데이터 체크
            if (typeof oCache.data.sHtml === 'undefined') {
                throw 'Invalid cache data.';
            }
            this.displayCouponDownload(oCache.data.sHtml, 'T');
        } catch(e) {
            // 복구 실패시 캐시 삭제
            this.removeCouponCache();
            return false;
        }
        return true;
    },

    /**
     * 쿠폰 html캐시 삭제
     */
    removeCouponCache : function()
    {
        // 캐시 삭제
        window.sessionStorage.removeItem(this.sCouponCacheKey);
    }
};
/**
 * jQuery JSON Plugin
 * version: 2.3 (2011-09-17)
 *
 * This document is licensed as free software under the terms of the
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * Brantley Harris wrote this plugin. It is based somewhat on the JSON.org
 * website's http://www.json.org/json2.js, which proclaims:
 * "NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.", a sentiment that
 * I uphold.
 *
 * It is also influenced heavily by MochiKit's serializeJSON, which is
 * copyrighted 2005 by Bob Ippolito.
 */

(function( $ ) {

    var escapeable = /["\\\x00-\x1f\x7f-\x9f]/g,
        meta = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };

    /**
     * jQuery.toJSON
     * Converts the given argument into a JSON respresentation.
     *
     * @param o {Mixed} The json-serializble *thing* to be converted
     *
     * If an object has a toJSON prototype, that will be used to get the representation.
     * Non-integer/string keys are skipped in the object, as are keys that point to a
     * function.
     *
     */
    $.toJSON = typeof JSON === 'object' && JSON.stringify
        ? JSON.stringify
        : function( o ) {

        if ( o === null ) {
            return 'null';
        }

        var type = typeof o;

        if ( type === 'undefined' ) {
            return undefined;
        }
        if ( type === 'number' || type === 'boolean' ) {
            return '' + o;
        }
        if ( type === 'string') {
            return $.quoteString( o );
        }
        if ( type === 'object' ) {
            if ( typeof o.toJSON === 'function' ) {
                return $.toJSON( o.toJSON() );
            }
            if ( o.constructor === Date ) {
                var month = o.getUTCMonth() + 1,
                    day = o.getUTCDate(),
                    year = o.getUTCFullYear(),
                    hours = o.getUTCHours(),
                    minutes = o.getUTCMinutes(),
                    seconds = o.getUTCSeconds(),
                    milli = o.getUTCMilliseconds();

                if ( month < 10 ) {
                    month = '0' + month;
                }
                if ( day < 10 ) {
                    day = '0' + day;
                }
                if ( hours < 10 ) {
                    hours = '0' + hours;
                }
                if ( minutes < 10 ) {
                    minutes = '0' + minutes;
                }
                if ( seconds < 10 ) {
                    seconds = '0' + seconds;
                }
                if ( milli < 100 ) {
                    milli = '0' + milli;
                }
                if ( milli < 10 ) {
                    milli = '0' + milli;
                }
                return '"' + year + '-' + month + '-' + day + 'T' +
                    hours + ':' + minutes + ':' + seconds +
                    '.' + milli + 'Z"';
            }
            if ( o.constructor === Array ) {
                var ret = [];
                for ( var i = 0; i < o.length; i++ ) {
                    ret.push( $.toJSON( o[i] ) || 'null' );
                }
                return '[' + ret.join(',') + ']';
            }
            var name,
                val,
                pairs = [];
            for ( var k in o ) {
                type = typeof k;
                if ( type === 'number' ) {
                    name = '"' + k + '"';
                } else if (type === 'string') {
                    name = $.quoteString(k);
                } else {
                    // Keys must be numerical or string. Skip others
                    continue;
                }
                type = typeof o[k];

                if ( type === 'function' || type === 'undefined' ) {
                    // Invalid values like these return undefined
                    // from toJSON, however those object members
                    // shouldn't be included in the JSON string at all.
                    continue;
                }
                val = $.toJSON( o[k] );
                pairs.push( name + ':' + val );
            }
            return '{' + pairs.join( ',' ) + '}';
        }
    };

    /**
     * jQuery.evalJSON
     * Evaluates a given piece of json source.
     *
     * @param src {String}
     */
    $.evalJSON = typeof JSON === 'object' && JSON.parse
        ? JSON.parse
        : function( src ) {
        return eval('(' + src + ')');
    };

    /**
     * jQuery.secureEvalJSON
     * Evals JSON in a way that is *more* secure.
     *
     * @param src {String}
     */
    $.secureEvalJSON = typeof JSON === 'object' && JSON.parse
        ? JSON.parse
        : function( src ) {

        var filtered =
            src
            .replace( /\\["\\\/bfnrtu]/g, '@' )
            .replace( /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
            .replace( /(?:^|:|,)(?:\s*\[)+/g, '');

        if ( /^[\],:{}\s]*$/.test( filtered ) ) {
            return eval( '(' + src + ')' );
        } else {
            throw new SyntaxError( 'Error parsing JSON, source is not valid.' );
        }
    };

    /**
     * jQuery.quoteString
     * Returns a string-repr of a string, escaping quotes intelligently.
     * Mostly a support function for toJSON.
     * Examples:
     * >>> jQuery.quoteString('apple')
     * "apple"
     *
     * >>> jQuery.quoteString('"Where are we going?", she asked.')
     * "\"Where are we going?\", she asked."
     */
    $.quoteString = function( string ) {
        if ( string.match( escapeable ) ) {
            return '"' + string.replace( escapeable, function( a ) {
                var c = meta[a];
                if ( typeof c === 'string' ) {
                    return c;
                }
                c = a.charCodeAt();
                return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
            }) + '"';
        }
        return '"' + string + '"';
    };

})( jQuery );

/**
 * jQuery JSON Plugin
 * version: 2.3 (2011-09-17)
 *
 * This document is licensed as free software under the terms of the
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * Brantley Harris wrote this plugin. It is based somewhat on the JSON.org
 * website's http://www.json.org/json2.js, which proclaims:
 * "NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.", a sentiment that
 * I uphold.
 *
 * It is also influenced heavily by MochiKit's serializeJSON, which is
 * copyrighted 2005 by Bob Ippolito.
 */

(function( $ ) {

    var escapeable = /["\\\x00-\x1f\x7f-\x9f]/g,
        meta = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };

    /**
     * jQuery.toJSON
     * Converts the given argument into a JSON respresentation.
     *
     * @param o {Mixed} The json-serializble *thing* to be converted
     *
     * If an object has a toJSON prototype, that will be used to get the representation.
     * Non-integer/string keys are skipped in the object, as are keys that point to a
     * function.
     *
     */
    $.toJSON = typeof JSON === 'object' && JSON.stringify
        ? JSON.stringify
        : function( o ) {

        if ( o === null ) {
            return 'null';
        }

        var type = typeof o;

        if ( type === 'undefined' ) {
            return undefined;
        }
        if ( type === 'number' || type === 'boolean' ) {
            return '' + o;
        }
        if ( type === 'string') {
            return $.quoteString( o );
        }
        if ( type === 'object' ) {
            if ( typeof o.toJSON === 'function' ) {
                return $.toJSON( o.toJSON() );
            }
            if ( o.constructor === Date ) {
                var month = o.getUTCMonth() + 1,
                    day = o.getUTCDate(),
                    year = o.getUTCFullYear(),
                    hours = o.getUTCHours(),
                    minutes = o.getUTCMinutes(),
                    seconds = o.getUTCSeconds(),
                    milli = o.getUTCMilliseconds();

                if ( month < 10 ) {
                    month = '0' + month;
                }
                if ( day < 10 ) {
                    day = '0' + day;
                }
                if ( hours < 10 ) {
                    hours = '0' + hours;
                }
                if ( minutes < 10 ) {
                    minutes = '0' + minutes;
                }
                if ( seconds < 10 ) {
                    seconds = '0' + seconds;
                }
                if ( milli < 100 ) {
                    milli = '0' + milli;
                }
                if ( milli < 10 ) {
                    milli = '0' + milli;
                }
                return '"' + year + '-' + month + '-' + day + 'T' +
                    hours + ':' + minutes + ':' + seconds +
                    '.' + milli + 'Z"';
            }
            if ( o.constructor === Array ) {
                var ret = [];
                for ( var i = 0; i < o.length; i++ ) {
                    ret.push( $.toJSON( o[i] ) || 'null' );
                }
                return '[' + ret.join(',') + ']';
            }
            var name,
                val,
                pairs = [];
            for ( var k in o ) {
                type = typeof k;
                if ( type === 'number' ) {
                    name = '"' + k + '"';
                } else if (type === 'string') {
                    name = $.quoteString(k);
                } else {
                    // Keys must be numerical or string. Skip others
                    continue;
                }
                type = typeof o[k];

                if ( type === 'function' || type === 'undefined' ) {
                    // Invalid values like these return undefined
                    // from toJSON, however those object members
                    // shouldn't be included in the JSON string at all.
                    continue;
                }
                val = $.toJSON( o[k] );
                pairs.push( name + ':' + val );
            }
            return '{' + pairs.join( ',' ) + '}';
        }
    };

    /**
     * jQuery.evalJSON
     * Evaluates a given piece of json source.
     *
     * @param src {String}
     */
    $.evalJSON = typeof JSON === 'object' && JSON.parse
        ? JSON.parse
        : function( src ) {
        return eval('(' + src + ')');
    };

    /**
     * jQuery.secureEvalJSON
     * Evals JSON in a way that is *more* secure.
     *
     * @param src {String}
     */
    $.secureEvalJSON = typeof JSON === 'object' && JSON.parse
        ? JSON.parse
        : function( src ) {

        var filtered =
            src
            .replace( /\\["\\\/bfnrtu]/g, '@' )
            .replace( /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
            .replace( /(?:^|:|,)(?:\s*\[)+/g, '');

        if ( /^[\],:{}\s]*$/.test( filtered ) ) {
            return eval( '(' + src + ')' );
        } else {
            throw new SyntaxError( 'Error parsing JSON, source is not valid.' );
        }
    };

    /**
     * jQuery.quoteString
     * Returns a string-repr of a string, escaping quotes intelligently.
     * Mostly a support function for toJSON.
     * Examples:
     * >>> jQuery.quoteString('apple')
     * "apple"
     *
     * >>> jQuery.quoteString('"Where are we going?", she asked.')
     * "\"Where are we going?\", she asked."
     */
    $.quoteString = function( string ) {
        if ( string.match( escapeable ) ) {
            return '"' + string.replace( escapeable, function( a ) {
                var c = meta[a];
                if ( typeof c === 'string' ) {
                    return c;
                }
                c = a.charCodeAt();
                return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
            }) + '"';
        }
        return '"' + string + '"';
    };

})( EC$ || jQuery );

/**
 * 상품연동형 js - for 프론트
 */


(function($) {

    var $Olnk = {
         iOlinkTotalPrice  : 0, // 저장형 옵션의 가격
         iAddOptionTotalPrice  : 0, // 추가 구성상품의 가격
         aOptionData : new Array(), // 순차적 로딩을 위한 배열
         iOptionAddNum : 1, // 필수값을 표시하기 위한 번호
         iOptionAddProductNum : 1,
         aOptionAddProductNum : new Array(),
         aOptionProductData : new Array(),
         aOptionProductDataListKey : new Array(),
         bAllSelectedOption : false,

         getOlnkSelectedItem : function(aStockData, bButton, sDispNonePrice, iProductPrice)
         {
             var aOption = new Array();
             var bItemSelected = false;
             var bResult = true;
             var sOptionId = '';
             var iOptPrice = 0;
             var iPrdPrice = SHOP_PRICE.toShopPrice(iProductPrice);

             // 운영방식설정 > 회원/비회원 가격표시 설정 반영
             if (sDispNonePrice == 'T') {
                 iTotalPrice = 0;
             } else {
                 var sSelector = 'select[id^="'+product_option_id+'"]';
                 if (typeof(EC_SHOP_FRONT_PRODUCT_FUNDING) === 'object' && EC_SHOP_FRONT_PRODUCT_FUNDING.isFundingProduct() === true) {
                     var sCompositionCode = EC$(EC_SHOP_FRONT_NEW_OPTION_BIND.oOptionObject).attr('composition-code');
                     sSelector = 'select[id^="'+product_option_id+'_'+sCompositionCode+'"]';
                 }
                 EC$(sSelector).each(function() {
                     var iValNo = parseInt(EC$(this).val());

                     if (isNaN(iValNo) === true) {
                         return;
                     }

                     iOptPrice += SHOP_PRICE.toShopPrice(aStockData[iValNo].option_price);
                     sOptionId =  iValNo;

                 });

                 iTotalPrice = iPrdPrice + iOptPrice;
             }

             EC$(sSelector).each(function() {

                 if (EC$(this).prop('required') === false && EC$(this).val() === '*') {
                     return true;
                 }
                 aOption.push(EC$(this).val());
             });

             // 전부 선택인 옵션만 있고 선택된 옵션이 없을때
             if ((Olnk.bAllSelectedOption === true || bButton === true) && aOption.length === 0) {
                 bItemSelected = true;
                 sOptionId = sProductCode;
             } else if (ITEM.isOptionSelected(aOption) === true) {
                 bItemSelected = true;
             }

          // 버튼으로 처리 했을때 선택이 모두 되어 있지 않다면 튕겨 내자
             if (bButton === true && bItemSelected === false && aOption.length > 0) {
                 alert(__('필수 옵션을 선택해주세요.'));
                 bResult = false;
             }

             // 추가입력옵션 체크!!
             if (bButton === true && checkAddOption() === false) {
                 bItemSelected = false;
             }

             return {'bResult' : bResult, 'bItemSelected' : bItemSelected, 'aOption' : aOption, 'sOptionId' : sOptionId, 'iTotalPrice' : iTotalPrice};
         },

        /**
         * 최종가격 표시 핸들링 - 상품상세
         */
        handleTotalPrice : function(sOptionStockData, iProductPrice, sDispNonePrice, bButton, iManualQuantity) {
            var aStockData = EC_UTIL.parseJSON(option_stock_data);
            var sOptionId = '';
            var iTotalPrice = 0;
            var iCnt = 1;
            var sQuantity = '('+sprintf(__('%s개'), iCnt)+')';
            var sPrice = '';

            // 옵션 선택 완료 되었을때 check
            var aOption = new Array();
            var aRequiredData = new Array();
            var sOptionText = '';
            var aOptionText = new Array();
            var bItemSelected = false, bSoldOut = false;
            var iTotalQuantity = 0;

            var aItemSelectInfo = Olnk.getOlnkSelectedItem(aStockData, bButton, sDispNonePrice, iProductPrice);

            bResult = aItemSelectInfo.bResult;
            bItemSelected = aItemSelectInfo.bItemSelected;
            aOption = aItemSelectInfo.aOption;
            if (aItemSelectInfo.sOptionId !== '') {
                sOptionId = aItemSelectInfo.sOptionId;
            }
            iTotalPrice = aItemSelectInfo.iTotalPrice;


            if (bItemSelected === true) {
                var sOptionText   = '';
                var iStockNumber  = aStockData[sOptionId].stock_number;
                var bStock        = aStockData[sOptionId].use_stock;
                var useSoldOut    = aStockData[sOptionId].use_soldout;
                var sIsDisplay    = aStockData[sOptionId].is_display;
                var sIsSelling    = aStockData[sOptionId].is_selling;
                var sIsReserveStat    = aStockData[sOptionId].is_reserve_stat; //예약주문R|Q당일발송
                if (typeof(EC_SHOP_FRONT_PRODUCT_FUNDING) === 'object' && EC_SHOP_FRONT_PRODUCT_FUNDING.isFundingProduct() === true) {
                    useSoldOut = 'F';
                }

                var iBuyUnit  = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getBuyUnitQuantity('base');
                var iProductMin = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity();

                var iQuantity = (iBuyUnit >= iProductMin ? iBuyUnit : iProductMin);

                if (typeof(iManualQuantity) !== 'undefined') {
                    iQuantity = iManualQuantity;
                }
                if (sIsSelling == 'F' || ((iStockNumber < buy_unit || iStockNumber <= 0) && ( (bStock === true  && useSoldOut === 'T' ) || sIsDisplay == 'F'))) {
                    bSoldOut = true;
                    sOptionText =  ' <span class="soldOut">['+__('품절')+']</span>';
                }

                if (bSoldOut === true && isNewProductSkin() === false) {
                    alert(__('이 상품은 현재 재고가 부족하여 판매가 잠시 중단되고 있습니다.') + '\n\n' + __('제품명') + ' : ' + product_name );
                    return;
                }

                //( 품절 or 추가메시지)
                if (aReserveStockMessage['show_stock_message'] === 'T' && sIsReserveStat !== 'N') {
                    var sReserveStockMessage = '';
                    bSoldOut = false; //품절 사용 안함

                    sReserveStockMessage = aReserveStockMessage[sIsReserveStat];
                    sReserveStockMessage = sReserveStockMessage.replace(aReserveStockMessage['stock_message_replace_name'], iStockNumber);
                    sReserveStockMessage = sReserveStockMessage.replace('[:PRODUCT_STOCK:]', iStockNumber);

                    sOptionText = sOptionText.replace(sReserveStockMessage, '') + ' <span class="soldOut">'+sReserveStockMessage+'</span>';
                }

                // 옵션 선택시 재고 수량이 현재 선택되어진 수량보다 적을 경우 alert처리후에 return합니다.
                EC$('.option_box_id').each(function(i) {
                    sQuantitySelector = '#' + EC$(this).attr('id').replace('id','quantity');
                    if (typeof(EC_SHOP_FRONT_PRODUCT_FUNDING) === 'object' && EC_SHOP_FRONT_PRODUCT_FUNDING.isFundingProduct() === true) {
                        var sCompositionCode = EC$(this).attr('composition-code');
                        sQuantitySelector = '#quantity_'+sCompositionCode;
                    }
                    iTotalQuantity += parseInt(EC$(sQuantitySelector).val());
                });

                if (iTotalQuantity > 0) {
                    iTotalQuantity += iQuantity;
                    if (((iStockNumber < iTotalQuantity || iStockNumber <= 0) && ((bStock === true  && useSoldOut === 'T' ) || sIsDisplay == 'F'))) {
                        alert(__('재고 수량이 부족하여 더 이상 옵션을 추가하실 수 없습니다.'));
                        return;
                    }
                }

                var sSelector = 'select[id^="'+product_option_id+'"]';
                if (typeof(EC_SHOP_FRONT_PRODUCT_FUNDING) === 'object' && EC_SHOP_FRONT_PRODUCT_FUNDING.isFundingProduct() === true) {
                    var sCompositionCode = EC$(EC_SHOP_FRONT_NEW_OPTION_BIND.oOptionObject).attr('composition-code');
                    if (EC_SHOP_FRONT_NEW_OPTION_BIND.oOptionObject === null) {
                        sCompositionCode = EC_SHOP_FRONT_PRODUCT_FUNDING.sCurrentCompositionCode;
                    }
                    sSelector = 'select[id^="'+product_option_id+'_'+sCompositionCode+'"]';
                }

                sOptionId = '';
                if ((Olnk.bAllSelectedOption === true || bButton === true) && aOption.length === 0) {
                    EC$(sSelector).each(function() {
                        sSelectedOptionId = EC$(this).attr('id');
                        sOptionId += EC$(this).val() + '_'+EC$(this).attr('option_code') +'||';
                    });
                    aOptionText.push( __('선택한 옵션 없음'));
                } else {
                    EC$(sSelector).each(function() {
                        if (EC$(this).prop('required') === false && EC$(this).val() === '*') {
                            return true;
                        }
                        if (Olnk.getCheckValue(EC$(this).val(),'') === true) {
                            sSelectedOptionId = EC$(this).attr('id');
                            aOptionText.push( EC$('#'+sSelectedOptionId+ ' option:selected').text());
                        }
                        sOptionId += EC$(this).val() + '_'+EC$(this).attr('option_code') +'||';
                    });
                }

                iProductPrice = getProductPrice(iQuantity, iTotalPrice, sOptionId, bSoldOut, function(iProductPrice){
                    if (isNewProductSkin() === false) {
                        if (sIsDisplayNonmemberPrice == 'T') {
                            EC$('#span_product_price_text').html(sNonmemberPrice);
                        } else {
                            EC$('#span_product_price_text').html(SHOP_PRICE_FORMAT.toShopPrice(iProductPrice));
                        }
                    } else {
                        setOptionBox(sOptionId, (aOptionText.join('/')) + ' ' + sOptionText , iProductPrice, bSoldOut, sSelectedOptionId, sIsReserveStat, iManualQuantity);
                    }

                });


            }

        },
        getOlinkOptionKey : function()
        {
            var sOptionId = '';
            EC$('select[id^="' + product_option_id + '"]').each(function() {
                if (EC$(this).prop('required') === false && EC$(this).val() === '*') {
                    return true;
                }
                sOptionId += EC$(this).val() + '_'+EC$(this).attr('option_code') +'||';
            });
            return sOptionId;
        },

        /**
         * 장바구니 담기시 필요한 파라미터 생성
         */
        getSelectedItemForBasket : function(sProductCode, oTargets, iQuantity) {
            var options = {};
            var aOptionData ,aOptionTmp;
            var bCheckNum = false;
            oTargets.each(function() {
                aOptionData = {};

                if (EC$(this).val().indexOf('||') >= 0) {
                    aOptionTmp = EC$(this).val().split('||');
                    for (i = 0; i < aOptionTmp.length; i++) {
                        if (aOptionTmp[i] !== '') {
                            aOptionData = aOptionTmp[i].split('_');
                        }

                        if (Olnk.getCheckValue(aOptionData[0],'') === true) {
                            options[aOptionData[1]] = aOptionData[0];
                            bCheckNum = true;
                        }
                    }
                } else {
                    var optCode = EC$(this).attr('option_code');
                    var optValNo = parseInt(EC$(this).val());

                    if (optCode == '' || optCode == null) {
                        return null;
                    }
                    if (isNaN(optValNo) === true) {
                        optValNo = '';
                    }

                    if (optValNo !== '') {
                        options[optCode] = optValNo;
                        bCheckNum = true;
                    }

                }
            });


            return {
                'product_code' : sProductCode,
                'quantity' : iQuantity,
                'options' : options,
                'bCheckNum' : bCheckNum
            };
        },

        /**
         * 관심상품 담기시 필요한 파라미터 생성
         */
        getSelectedItemForWish : function(sProductCode, oTargets) {
            var options = {};
            var bCheckNum = false;

            var aOptionData ,aOptionTmp;
            EC$(oTargets).each(function() {

                aOptionTmp = EC$(this).val().split('||');
                aOptionData = {};
                options = {};

                for (i = 0; i < aOptionTmp.length; i++) {
                    if (aOptionTmp[i] !== '') {
                        aOptionData = aOptionTmp[i].split('_');
                    }
                    //if (/^\*+$/.test(aOptionData[0]) === false) {
                    if (Olnk.getCheckValue(aOptionData[0],'') === true) {
                        options[aOptionData[1]] = aOptionData[0];
                        bCheckNum = true;
                    }
                }
            });

            return {
                'product_code' : sProductCode,
                'options' : options,
                'bCheckNum' : bCheckNum
            };
        },

        /**
         * 선택된 품목정보 반환
         * 상품연동형에서는 item_code 가 선택한 옵션을 뜻하지 않으므로
         * 호환성을 위한 모조 값만 할당해준다.
         */
        getMockItemInfo : function(aInfo) {
            var aResult = {
                'product_no' : aInfo.product_no,
                'item_code' : aInfo.product_code + '000A',
                'opt_id' : '000A',
                'opt_str' : ''
            };

            return aResult;
        },

        /**
         * 상품연동형 옵션인지 여부 반환
         */
        isLinkageType : function(sOptionType) {
            if (typeof sOptionType == 'string' && sOptionType == 'E') {
                return true;
            }

            return false;
        },

        /**
         * 상품상세(NewProductAction) 관련 js 스크립트를 보면, create_layer 라는 함수가 있다.
         * 해당 함수는 ajax 콜을 해서 레이어 팝업으로 띄울 소스코드를 불러오게 되는데, 이때 스크립트 태그도 같이 따라온다.
         * 해당 스크립트 태그에서 불러오는 js 파일내부에는 동일한 jquery 코드가 다시한번 오버라이딩이 되는데
         * 이렇게 되면 기존에 물려있던 extension 메소드들은 초기화되어 날아가게 된다.
         *
         * 레이어 팝업이 뜨고 나서, $ 내에 존재해야할 메소드나 멤버변수들이 사라졌다면 이와 같은 현상때문이다.
         * 가장 이상적인 처리는 스크립트 태그를 없애는게 가장 좋으나 호출되는 스크립트에 의존적인 코드가 존재하는것으로 보인다.
         * 해당영역이 완전히 파악되기 전까진 필요한 부분에서만 예외적으로 동작할 수 있도록 한다.
         */
        bugfixCreateLayerForWish : function() {
            var __nil = jQuery.noConflict(true);
        },

        /**
         * 장바구니 담기시 필요한 파라미터를 일부 조작
         */
        hookParamForBasket : function(aParam, aInfo) {
            if (aInfo.option_type != 'E') {
                return aParam;
            }

            var aItemCode = this.getSelectedItemForBasket(aInfo.product_code, aInfo.targets, aInfo.quantity);

            aParam['item_code_before'] = '';
            aParam['option_type'] = 'E';
            aParam['selected_item_by_etype[]'] = EC$.toJSON(aItemCode);

            return aParam;
        },

        /**
         * 관심상품 담기시 필요한 파라미터를 일부 조작
         */
        hookParamForWish : function(aParam, aInfo) {
            if (aInfo.option_type != 'E') {
                return aParam;
            }

            var aItemCode = {};

            //
            // aInfo.targets 는 구스킨을 사용했을 때 출력되는 옵션 셀렉트 박스의 엘리먼트 객체인데,
            // 현재 뉴스킨과 구스킨 구분을 아이디값이 wishlist_option_modify_layer_ 에 해당되는 노드가
            // 있는지로 판별하기 때문에 모호함이 존재한다.
            // 즉, 뉴스킨을 사용해도 해당 노드가 존재하지 않는 조건이 발생할 수 있기 때문이다.
            // 예를 들면, 관심상품상에 담긴 리스트가 모두 옵션이 없는 상품만 있는 경우이거나 아니면
            // 옵션이 존재하지만 아무것도 선택되지 않은 상품인 경우 발견이 되지 않을 수 있다.
            // 그러므로 이런 경우엔 셀렉트박스를 통해 선택된 옵션을 파악하는 것이 아니라,
            // 현재 할당되어 있는 데이터를 기준으로 파라미터를 세팅하도록 한다.
            //
            if (aInfo.targets.length > 0) {
                aItemCode = this.getSelectedItemForBasket(aInfo.product_code, aInfo.targets, aInfo.quantity);
            } else {
                aItemCode = aInfo.selected_item_by_etype;
            }

            aParam.push('option_type=E');
            aParam.push('selected_item_by_etype[]=' + EC$.toJSON(aItemCode));

            return aParam;
        },
        /**
         * 장바구니 담기시 필요한 파라미터 생성 - 구스킨 전용 뉴스킨 사용안함.
         */
        getSelectedItemForBasketOldSkin : function(sProductCode, oTargets, iQuantity) {
            var options   = {};
            var optCode   = '';
            var optValNo  = '';
            var bCheckNum = false;
            oTargets.each(function() {
                optCode = EC$(this).attr('option_code');
                optValNo = parseInt(EC$(this).val());

                if (optCode == '' || optCode == null) {
                    return null;
                }

                if (isNaN(optValNo) === false) {
                    options[optCode] = EC$(this).val();
                    bCheckNum = true;
                }
            });

            return {
                'product_code' : sProductCode,
                'quantity' : iQuantity,
                'options' : options,
                'bCheckNum' : bCheckNum
            };
        },
        /**
         * 관심상품 담기시 필요한 파라미터 생성
         */
        getSelectedItemForWishOldSkin : function(sProductCode, oTargets) {
            var options = {};
            var isReturn = true;
            var bCheckNum = false;
            oTargets.each(function() {
                if (isReturn === false) {
                    isReturn = false;
                    return;
                }

                var optCode = EC$(this).attr('option_code');
                var optValNo = parseInt(EC$(this).val());

                //
                // 필수입력값 체크
                //
                if (EC$(this).prop('required') === true) {
                    if (isNaN(optValNo) === true) {
                        isReturn = false;
                        return false;
                    }
                }

                if (optCode == '' || optCode == null) {
                    isReturn = false;
                    return;
                }

                if (isNaN(optValNo) === false) {
                    options[optCode] = optValNo;
                    bCheckNum = true;
                }
            });

            if (isReturn === true) {
                return {
                    'product_code' : sProductCode,
                    'options' : options,
                    'bCheckNum' : bCheckNum
                };
            }

            return false;
        },

        /*
         * 상단 옵션 선택후 alert후 옵션 재세팅 ( 상위 옵션이 재 세팅되면 해당 옵션에 하단 옵션들은 reset)
         */
        getOptionCheckData : function(oTarget) {
            //if ((/^\*+$/.test(oTarget.val()) === true && Boolean(oTarget.prop('required')) === true) || oTarget.attr('id') === undefined) {
            return !((Olnk.getCheckValue(oTarget.val(), '') === false && oTarget.prop('required') === true) || oTarget.attr('id') === undefined);


        },
        /**
         * 재고 체크 ( 구스킨에서 action시에 필요함.
         * 각각의 수량을 전부 합치고 그 합친 수량과 재고 체크
         * @param sOptionId 옵션 id
         * @returns 품절여부
         */
        getStockValidate : function (sOptionId , iQuantity) {
            var aStockData = EC_UTIL.parseJSON(option_stock_data);
            var bSoldOut = false;
            var iStockNumber , bStock , bStockSoldOut;
            // get_stock_info
            if (aStockData[sOptionId] == undefined) {
                iStockNumber  = -1;
                bStock        = false;
                bStockSoldOut = 'F';
            } else {
                iStockNumber  = aStockData[sOptionId].stock_number;
                bStock        = aStockData[sOptionId].use_stock;
                bStockSoldOut = aStockData[sOptionId].use_soldout;

            }
            if (bStockSoldOut == 'T' && bStock === true && (iStockNumber < iQuantity)) {
                bSoldOut = true;
            }
            return bSoldOut;
        },
        /*
         * check value
         */
        getCheckValue : function (oTargetValue , oTarget) {
            if (/^\*+$/.test(oTargetValue) === true) {
                if (oTarget !== '') {
                    oTarget.val('*');
                }
                return false;
            }
            return true;
        },
        /*
         * 추가 구성상품의 재고 체크
         * @param aOptionBoxInfo 추가 구성상품 데이터
         */
        getAddProductStock : function (aOptionBoxInfo) {
            var iTotalQuantity = aOptionBoxInfo['iTotalQuantity'];
            if (this.isLinkageType(aOptionBoxInfo['option_type']) === true) {
                EC$('.option_add_box_'+aOptionBoxInfo['product_no']).each(function() {
                    // 수량 증가시 본인꺼는 빼야 한다..
                    if (aOptionBoxInfo['sOptionBoxId'] !== EC$(this).attr('id')) {
                        iTotalQuantity += parseInt(iQuantity = EC$('#' + EC$(this).attr('id').replace('id','quantity')).val());
                    }

                });
                if (aOptionBoxInfo['is_stock'] === true && aOptionBoxInfo['use_soldout'] === true && aOptionBoxInfo['stock_number'] < iTotalQuantity) {
                    alert(sprintf(__('%s 의 재고가 부족합니다.'), aOptionBoxInfo['title']));
                    //alert(aOptionBoxInfo['title'] + ' - ' + __('의 재고가 부족합니다.'));
                    return false;
                }
            }
        },
        /*
         * 모든 상품의 옵션이 선택일때 옵션박스가 떨궈지지 않았을 경우 (아무것도 선택안하면 option_box 안생김)
         * @param aOptionBoxInfo 추가 구성상품 데이터
         */
        getProductAllSelected : function (sProductCode, oTargets, iQuantity) {
            var bAllSelected = true;
            var options = {};
            oTargets.each(function(i) {
                if (EC$(this).val().indexOf('||') >= 0) {
                    aOptionTmp = EC$(this).val().split('||');
                    for (i = 0; i < aOptionTmp.length; i++) {
                        if (aOptionTmp[i] !== '') {
                            aOptionData = aOptionTmp[i].split('_');
                        }
                        options[aOptionData[1]] = '';
                    }
                } else {
                    if (EC$(this).prop('required') === true || Olnk.getCheckValue(EC$(this).val() , '') === true) {
                        bAllSelected = false;
                        return false;
                    }
                    var optCode  = EC$(this).attr('option_code');
                    var optValNo = parseInt(EC$(this).val());

                    if (optCode == '' || optCode == null) {
                        return null;
                    }
                    if (isNaN(optValNo) === true) {
                        optValNo = '';
                    }
                    options[optCode] = optValNo;
                }
            });

            if (bAllSelected === true) {
                return {
                    'product_code' : sProductCode,
                    'quantity' : iQuantity,
                    'options' : options
                };
            } else {
                return false;
            }

        },

        /*
         * 옵션 추가버튼 ( 신규 스킨의 연동형 옵션일때 품목 추가 버튼 생김)
         * totalProducts가 있을때 신규 스킨
         * ( NewProductOption.js에 isNewProductSkin이 있지만 의존적 처리가 어려움)
         * oPushButton 품목 추가 버튼 Object
         */
        getOptionPushbutton : function(oPushButton) {
            if (typeof(option_push_button) !== 'undefined' && option_push_button === 'T' &&  oPushButton.length >  0  && isNewProductSkin() === true) {
                return true;
            } else {
                return false;
            }

        },

        /*
         * 옵션 추가버튼 action. php 에서 assign된 함수
         */
        setOptionPushButton : function(){
            Olnk.handleTotalPrice(option_stock_data, product_price, sIsDisplayNonmemberPrice , true);
        },
        /**
         * 옵션 추가 버튼 연동형 옵션인 경우에만 동작 하자.(이건 추가구성상품)
         * @param iProductNum 상품번호
         */
        setAddOptionPushButton : function(iProductNum) {
            ProductAdd.setAddProductOptionPushButton(iProductNum);
        },
        setSetOptionPushButton : function(iProductNum) {
            ProductSet.setSetProductOptionPushButton(iProductNum);
        },

        /**
         * custom_data에 필요한 품주코드
         * @param {string} sProductCode 상품코드
         * @param {int} iTotalOptionCount 선택된 품목 개수
         * @param {int} iCurrentIndex 현재 인덱스
         * @returns {string}
         */
        getCustomOptionItemCode: function (sProductCode, iTotalOptionCount, iCurrentIndex) {
            return sProductCode + '000A_' + (iTotalOptionCount - iCurrentIndex);
        }
    };

    //
    // 공개 인터페이스
    //
    window['Olnk'] = $Olnk;

})($);


/**
 * 품절품목일 경우 품절 문구에대한 처리
 */
var EC_SHOP_FRONT_NEW_OPTION_EXTRA_SOLDOUT = {
    /**
     * 품절문구 설정 정보
     */
    aSoldoutText : null,

    /**
     * 필수 메서드
     * @param iProductNum 상품번호
     * @param sItemCode 품목코드
     * @returns 설정에따라 품절문구 리턴
     * @final
     */
    get : function(iProductNum, sItemCode) {
        return this.getStockText(iProductNum, sItemCode);
    },

    /**
     * 품절문구 설정(기존로직 그대로
     * @param iProductNum 상품번호
     * @returns 해당 상품의 품절 설정 문구
     */
    getSoldoutDiplayText: function (iProductNum) {
        if (typeof(aSoldoutDisplay) === 'undefined') {
            return EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_SOLDOUT_DEFAULT_TEXT;
        }

        if (this.aSoldoutText === null) {
            if (typeof(aSoldoutDisplay) === 'string') {
                this.aSoldoutText = EC_UTIL.parseJSON(aSoldoutDisplay);
            } else {
                this.aSoldoutText = aSoldoutDisplay;
            }
        }

        if (typeof(this.aSoldoutText[iProductNum]) === 'undefined') {
            this.aSoldoutText[iProductNum] = EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_SOLDOUT_DEFAULT_TEXT;
        }

        return this.aSoldoutText[iProductNum];
    },

    /**
     * 해당 품목이 품목일 경우 표시될 품절표시 Text
     * @param iProductNum 상품번호
     * @param sItemCode 아이템코드
     * @returns 표시될 품절문구
     */
    getStockText : function(iProductNum, sItemCode) {
        var sSoldoutText = '';
        var bIsSoldout = EC_SHOP_FRONT_NEW_OPTION_COMMON.isSoldout(iProductNum, sItemCode);

        if (bIsSoldout === true) {
            sSoldoutText = ' [' + this.getSoldoutDiplayText(iProductNum) + ']';
        }

        if (typeof(aReserveStockMessage) === 'undefined') {
            return sSoldoutText;
        }

        var aStockData = EC_SHOP_FRONT_NEW_OPTION_COMMON.getProductStockData(iProductNum);

        if (typeof(aStockData[sItemCode]) === 'undefined') {
            return sSoldoutText;
        }

        if (aStockData[sItemCode].is_reserve_stat !== 'N') {
            sSoldoutText = aReserveStockMessage[aStockData[sItemCode].is_reserve_stat];
            sSoldoutText = sSoldoutText.replace(aReserveStockMessage['stock_message_replace_name'], aStockData[sItemCode].stock_number);
            sSoldoutText = sSoldoutText.replace('[:PRODUCT_STOCK:]', aStockData[sItemCode].stock_number);
        }

        return sSoldoutText;
    }
};

/**
 * 해당 품목에 대한 추가금액 표시여부
 */
var EC_SHOP_FRONT_NEW_OPTION_EXTRA_PRICE = {
    /**
     * 추가금액 표시여부 설정
     */
    aOptionPriceDisplayConf : [],

    oChooseObject : null,

    /**
     * 필수 메서드
     * @param iProductNum 상품번호
     * @param sItemCode 품목코드
     * @param eChooseObject 현재 선택한 옵션 Object
     * @returns 설정에따라 표시할 경우 품목의 추가금액 리턴
     * @final
     */
    get : function(iProductNum, sItemCode, eChooseObject) {
        this.oChooseObject = eChooseObject;
        return this.getAddPriceText(iProductNum, sItemCode);
    },

    /**
     * 각 옵션선택시마다 실행되는 가격관련 메서드
     * @param oOptionChoose 구분할 옵션박스 object
     * @returns bool
     */
    eachCallback : function(oOptionChoose) {
        //구스킨에서 옵션선택시마다 표시항목 판매가부분의 가격에 옵션추가듬액 계산
        this.setDisplayProductPriceForOldSkin(oOptionChoose);
    },

    /**
     * 구스킨에서 옵션선택시마다 표시항목 판매가부분의 가격에 옵션추가듬액 계산
     * @param oOptionChoose 구분할 옵션박스 object
     */
    setDisplayProductPriceForOldSkin : function(oOptionChoose) {
        //뉴스킨이라면 패스 (ECHOSTING-241102 모바일 관심상품리스트 오류)
        if (EC$('#totalProducts').length > 0) {
            return;
        }

        //해당 function이 존재할때만 실행
        if (typeof(setOldTotalPrice) !== 'function') {
            return;
        }

        var sID = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionChooseID(oOptionChoose);

        //상품상세 메인상품에 대해서만 실행
        if (/^product_option_id+/.test(sID) !== true) {
            return;
        }

        //구스킨일 경우 각 옵션선택시마다 실행
        try {
            setOldTotalPrice();
        } catch(e) {
            EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(oOptionChoose, '*');
        }
    },

    /**
     * 옵션 추가금액에대한 Display텍스트
     * @param iProductNum 상품번호
     * @param sItemCode 품목코드
     * @returns string 옵션 추가금액 Text
     */
    getAddPriceText : function(iProductNum, sItemCode) {
        //추가금액 표시여부
        var bIsDisplayOptionPrice = this.getOptionPriceDisplay(iProductNum);

        if (bIsDisplayOptionPrice === false) {
            return '';
        }

        var iAddPrice = this.getAddPrice(iProductNum, sItemCode);

        if (iAddPrice !== false) {
            var sPrefix = '';
            if (iAddPrice > 0.00) {
                sPrefix = '+';
            } else {
                sPrefix = '-';
            }

            //화폐단위가 +- 기호 뒤에와야해서 여기서 양수로 바꿈
            iAddPrice = Math.abs(iAddPrice);

            var sStr =  ' (' + sPrefix + SHOP_PRICE_FORMAT.toShopPrice(iAddPrice) + ')';
            //그냥 값을 더할경우 원표시(\)가 &#8361;로 변환되어서 clone으로 다시가져오게 처리
            return EC$('<div>').append(sStr).html();
        }

        return '';
    },

    /**
     * 해당 품목의 추가금액을 가져온다(없을 경우에는 false를 리턴
     * @param iProductNum 상품번호
     * @param sItemCode 품목코드
     * @returns int|boolean 추가금액
     */
    getAddPrice : function(iProductNum, sItemCode) {
        var aStockData = EC_SHOP_FRONT_NEW_OPTION_COMMON.getProductStockData(iProductNum);
        if (typeof(aStockData[sItemCode].stock_price) !== 'undefined' && parseFloat(aStockData[sItemCode].stock_price) !== 0.00) {
            return parseFloat(aStockData[sItemCode].stock_price);
        }

        return false;
    },

    /**
     * 옵션 추가금액 표시여부 설정
     * @param iProductNum 상품번호
     * @returns 표시여부
     */
    getOptionPriceDisplay : function(iProductNum) {
        if (typeof(EC_SHOP_FRONT_NEW_OPTION_DATA.aOptionPriceDisplayConf[iProductNum]) === 'undefined') {
            return 'T';
        }

        return (EC_SHOP_FRONT_NEW_OPTION_DATA.aOptionPriceDisplayConf[iProductNum] === 'T');
    }
};

/**
 * 옵션 선택 또는 품목선택완료시 상세이미지 변경
 */
var EC_SHOP_FRONT_NEW_OPTION_EXTRA_IMAGE = {
    /**
     * 모바일과 상세이미지 클래스가 틀려서
     */
    sDetailImageClass : '',

    /**
     * 세트상품의 이미지 영역
     */
    sSetProductImageID : '',

    /**
     * 스와이프기능을 사용하는 상품상세인지 확인(모바일전용)
     */
    isSwipe : false,

    /**
     * 세트상품인지 여부
     */
    bIsSetProduct : false,

    /**
     * 각 옵션선택시마다 이미지가 있다면 상세이미지에 반영되도록 함
     * @param oOptionChoose 구분할 옵션박스 object
     */
    eachCallback : function(oOptionChoose) {
        this.bIsSetProduct = false;

        //세트상품일 경우에 대한 처리
        if (/^setproduct_option_+/.test(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectGroup(oOptionChoose)) === true) {
            this.bIsSetProduct = true;
            this.sSetProductImageID = '#ec-set-product-composed-product-';
        }

        if (/^addproduct_option_+/.test(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectGroup(oOptionChoose)) === true) {
            this.bIsSetProduct = true;
            this.sSetProductImageID = '#ec-add-product-composed-product-';
        }

        if (this.isDisplayImage(oOptionChoose) === false) {
            return;
        }

        var oSelectedOption = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectedElement(oOptionChoose);

        if (typeof(oSelectedOption.attr('link_image')) === 'undefined' || oSelectedOption.attr('link_image').length < 1) {
            return;
        }

        this.setImage(oSelectedOption.attr('link_image'), true, oOptionChoose);
    },

    /**
     * 옵션 전체 선택완료후 해당 옵션품목에 연결된 이미지를 상세이미지에 반영되도록 함
     * @param oOptionChoose 구분할 옵션박스 object
     */
    completeCallback : function(oOptionChoose) {
        //연동형은 제외
        if (Olnk.isLinkageType(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionType(oOptionChoose)) === true) {
            return;
        }

        if (this.isDisplayImage(oOptionChoose) === false) {
            return;
        }

        var sItemCode = EC_SHOP_FRONT_NEW_OPTION_COMMON.getItemCode(oOptionChoose);

        if (sItemCode === false) {
            return;
        }

        var iProductNo = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionProductNum(oOptionChoose);

        var aStockData = EC_SHOP_FRONT_NEW_OPTION_DATA.getProductStockData(iProductNo);

        if (typeof(aStockData[sItemCode].item_image_file) !== 'undefined' && EC_UTIL.trim(aStockData[sItemCode].item_image_file) !== '') {
            this.setImage(aStockData[sItemCode].item_image_file, false, oOptionChoose);
        }
    },

    /**
     * 이미지 출력이 가능한지 확인
     * @param oOptionChoose 구분할 옵션박스 object
     * @returns bool
     */
    isDisplayImage : function(oOptionChoose) {
        //세트상품일 경우에 대한 처리
        if (this.bIsSetProduct === true) {
            return this.isDisplayImageDesignForSetProduct(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionProductNum(oOptionChoose));
        } else {
            //추가구성상품등은 모두 제외하고 상품상세의 대표상품만 변경
            return this.isDisplayImageDesign();
        }
    },

    /**
     * 세트상품에서 구성상품의 옵션선택시 이미비 변경 가능여부
     * @param iProductNum 상품번호
     * @returns {boolean}
     */
    isDisplayImageDesignForSetProduct : function(iProductNum)
    {
        var oSetProductImageElement = EC$(this.sSetProductImageID + iProductNum);

        //해당 구성상품의 이미지영역이 없거나 id가 지정되지 않았으면 false
        if (oSetProductImageElement.length < 1) {
            return false;
        }

        return true;
    },

    /**
     * 디자인에서 이미지가 노출될수있는 디자인인지 확인
     * 상품상세에서 동일하게 사용하기위해서 따로 메서드로 분리
     * @returns {boolean}
     */
    isDisplayImageDesign : function()
    {
        var isMobile = false;
        if (typeof(mobileWeb) !== 'undefined' && mobileWeb === true) {
            isMobile = true;
            this.sDetailImageClass = '.bigImage';
        } else {
            this.sDetailImageClass = '.BigImage';
        }

        if (isMobile === true) {
            if (EC$('.xans-product-mobileimage').length > 0) {
                this.isSwipe = true;
            }
        }

        //상세이미지가 없다면 패스
        if (this.isSwipe === false && EC$(this.sDetailImageClass).length < 1) {
            return false;
        }

        return true;
    },

    /**
     * 각 디자인에 따라 옵션 도는 품목이미지를 상세이미지에 노출
     * @param sUrl 이미지주소
     * @param isOptionFlag 각 옵션의 이미지가 아닌 모든옵션 선택후 품목의 이미지이면 false
     * @param oOptionChoose 구분할 옵션박스 object
     */
    setImage : function(sUrl, isOptionFlag, oOptionChoose)
    {
        if (this.bIsSetProduct === true) {
            EC$(this.sSetProductImageID + EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionProductNum(oOptionChoose)).attr('src', sUrl);
        } else {
            //스와이프기능을 사용할때
            if (this.isSwipe === true) {
                if (isOptionFlag === false) {
                    //모든 옵션 선택후 품목에 연결이미지
                    this.setSwipeImage(sUrl, false);
                } else {
                    //각 업션에 대한 옵션이미지
                    this.setSwipeImage('', true);

                    var iIndex = EC$('div.xans-product-mobileimage').find('li img[src="' + sUrl + '"]').parent().index();
                    EC$('div.typeSwipe').find('span > button').eq(iIndex).trigger('click');
                }
            } else {
                //스와이프기능을 사용안할때
                EC$(this.sDetailImageClass).attr('src', sUrl);
            }
        }
    },

    /**
     * 모바일 상품상세에서 스와이프 사용중일때
     * 각 옵션의 연결이미지는 기존스와이프 영역으로
     * 각 품목의 연결이미지는 원래 대표이미지 영역이 나오도록 함
     * @param sSrc 해당 이미지 주소(품목 연결이미지일떄만)
     * @param bIsShowSlide true => 각 옵션별 연결이미지, false => 각 품목별 연결이미지
     * @param iButtonIndex 이미지 선택 버튼 순서값
     * @param oTarget 처리할 스와이프 모듈 Object
     */
    setSwipeImage : function(sSrc, bIsShowSlide, iButtonIndex, oTarget)
    {
        var oElement = EC$('div.xans-product-mobileimage');
        var oSwipe = EC$('div.typeSwipe');
        if (bIsShowSlide === true) {
            oElement.find('ul.eOptionImageCloneTemplate').remove();
            oElement.find('ul').show();

            //품목이미지가 노출된후 다시 슬라이드버튼을 누르때 시간차로인대 css가 먹지 않아서 추가
            if (typeof(iButtonIndex) !== 'undefined') {

                // 만약 파라미터로 받은 특정 스와이프 모듈 Object가 존재하면 해당 모듈에서만 처리하도록 추가
                if (typeof(oTarget) !== 'undefined') {
                    oSwipe = oTarget;
                }

                oSwipe.find('span > button').eq(iButtonIndex).addClass('selected');
            }
        } else {

            //첫번째 이미지를 기준으로 height가 정해지기때문에
            //두번째 이미지에 할당함
            oSwipe.find('span > button').eq(1).trigger('click');
            var oClone = oElement.find('ul').clone();
            oClone.addClass('eOptionImageCloneTemplate');

            //추가이미지가 1개만 있을경우에는 따로 삭제하지 않음
            //추가이미지가 하개이면 버튼이 원래 두개이므로 따로 삭제하지 않아도 됨
            if (oSwipe.find('span > button').length > 2) {
                oClone.find('li').not('li').eq(0).not('li').eq(1).remove();
            }
            oClone.find('li').eq(1).find('img').attr('src', sSrc);

            oSwipe.find('span > button').removeClass('selected');

            oElement.find('ul').hide();

            oElement.find('ul').eq(0).before(oClone);
        }
    }
};

/**
 * 버튼 또는 미리보기 옵션일 경우 지정된 엘리먼트에 선택한 옵션값 보여주기
 */
var EC_SHOP_FRONT_NEW_OPTION_EXTRA_DISPLAYITEM = {
    TARGET_ELEMENT_CLASS : '.ec-shop-front-product-option-desc-trigger',
    /**
     * 각 옵션선택시마다 이미지가 있다면 상세이미지에 반영되도록 함
     * @param oOptionChoose 구분할 옵션박스 objectboolean
     */
    eachCallback : function(oOptionChoose) {
        //버튼 또는 미리보기 옵션이 아니면 리턴
        if (EC_SHOP_FRONT_NEW_OPTION_COMMON.isOptionStyleButton(oOptionChoose) === false) {
            return;
        }

        //셀렉터에 ""를 안붙이면 가끔 특정상횡에서 스크립트오류
        var oTarget = EC$(oOptionChoose).parent().find("" + this.TARGET_ELEMENT_CLASS + "");

        //디자인이 없다면 패스
        if (EC$(oTarget).length < 1) {
            return;
        }

        var sText = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectedText(oOptionChoose);

        //선택항목에 text가 있다면
        //추후에 셀렉트박스가 추가된다면... *나 **가 선택되었다면 예외처리해야함
        if (typeof(sText) !== 'undefined' && EC_UTIL.trim(sText) !== '') {
            EC$(oTarget).removeClass('ec-product-value').addClass('ec-product-value');
            EC$(oTarget).html(sText);
        } else {
            EC$(oTarget).removeClass('ec-product-value');
            EC$(oTarget).html(EC$(oTarget).attr('data-option_msg'));
        }
    }
};
var EC_SHOP_FRONT_NEW_OPTION_EXTRA_ITEMSELECTION =
{
    oCommon : null,
    initObject : function()
    {
        if (this.oCommon !== null) {
            return;
        }
        this.oCommon = EC_SHOP_FRONT_NEW_OPTION_COMMON;
    },
    sOptionKey : null,
    prefetech : function(oOptionChoose)
    {
        this.initObject();

        if (oSingleSelection.isItemSelectionTypeM() === true) {
            return;
        }

        // 동일한 키로 선택된 상품이 없다면 prefetch는 할일이 없음
        var oTarget = this.getDeleteTriggerElement(oOptionChoose);
        if (oTarget.length === 0) {
            return;
        }

        if (this.oCommon.getOptionType(oOptionChoose) === 'F') {
            return this.prefetchOptionTypeF(oOptionChoose);
        }

        if (this.oCommon.getOptionType(oOptionChoose) === 'E') {
            return this.prefetchOptionTypeE(oOptionChoose);
        }

        oTarget.click();
    },
    prefetchOptionTypeE : function(oOptionChoose)
    {
        var sOptionGroup = this.oCommon.getOptionSelectGroup(oOptionChoose);
        if (sOptionGroup.indexOf('setproduct') < 0) {
            this.getDeleteTriggerElement(oOptionChoose).click();
            return;
        }
        var oTarget = this.getDeleteTriggerElement(oOptionChoose);

        var sOptionId = oTarget.attr('id').substring(0, oTarget.attr('id').lastIndexOf('_'));
        var sOptionKey = EC$('#'+sOptionId+'_id').val();
        this.sOptionKey = sOptionKey;

        var sContext = this.getDeleteTriggerContext(oOptionChoose);
        EC$(sContext).remove();

        this.hookIndividualSetProductParameter(sOptionKey);
    },
    prefetchOptionTypeF : function(oOptionChoose)
    {
        var sOptionGroup = this.oCommon.getOptionSelectGroup(oOptionChoose);
        var oTarget = this.getDeleteTriggerElement(oOptionChoose);
        var sOptionId = oTarget.attr('id').substring(0, oTarget.attr('id').lastIndexOf('_'));
        var sOptionKey = EC$('#'+sOptionId+'_id').val();
        this.sOptionKey = sOptionKey;

        // 추가구성상품
        if (sOptionGroup.indexOf('addproduct') > -1) {
            var aOptionInfo = EC$('#'+sOptionId+'_id').val().split('||');
            sOptionKey = aOptionInfo[0];
            this.sOptionKey = sOptionKey;
            ProductAdd.delOptionBoxData(sOptionKey);
        }

        // 일반상품, 추가구성상품 동일
        TotalAddSale.removeProductData(sOptionKey);

        // 세트상품
        if (sOptionGroup.indexOf('setproduct') > -1) {
            this.hookIndividualSetProductParameter(sOptionKey);
        }
    },
    eachCallback : function(oOptionChoose)
    {
        if (oSingleSelection.isItemSelectionTypeM() === true) {
            return;
        }
        var sOptionType = this.oCommon.getOptionType(oOptionChoose);

        if (sOptionType === 'F') {
            return this.eachCallbackOptionTypeF(oOptionChoose);
        }
        if (sOptionType === 'E') {
            return this.eachCallbackOptionTypeE(oOptionChoose);
        }
    },
    eachCallbackOptionTypeE : function(oOptionChoose)
    {
        // 뭔가 값이 선택됐을때는 원래 돌던대로 돌린다
        if (this.oCommon.getOptionSelectedValue(oOptionChoose) !== '*') {
            return;
        }
        var sOptionGroup = this.oCommon.getOptionSelectGroup(oOptionChoose);
        // 선택한 값이 취소된 경우에만 이 로직을 실행한다
        // 모두 선택인 경우에는 하나라도 선택되었는지
        if (this.oCommon.validation.checkRequiredOption(sOptionGroup) === false) {
            bIsSelectedRequiredOption = this.oCommon.validation.isOptionSelected(oOptionChoose);
        } else {
            bIsSelectedRequiredOption = this.oCommon.validation.isSelectedRequiredOption(sOptionGroup);
        }
        // 뭔가 하나 선택되어있는 경우
        if (this.oCommon.getItemCode(oOptionChoose) === false && bIsSelectedRequiredOption === true) {
            var oOptionGroup = this.oCommon.getOptionLastSelectedElement(sOptionGroup);
            if (sOptionGroup.indexOf('addproduct') > -1) {
                var iProductNum = this.oCommon.getOptionProductNum(oOptionChoose);
                if (this.oCommon.isOptionStyleButton(oOptionChoose) === true) {
                    ProductAdd.setAddProductOptionPushButton(iProductNum);
                }
            } else {
                if (typeof(ProductSet) === 'object') {
                    if (this.oCommon.isOptionStyleButton(oOptionGroup) === true) {
                        var oOptionGroup = EC$('select[product_option_area_select="' + EC$(oOptionGroup).attr('product_option_area') + '"][id="' + EC$(oOptionGroup).attr('ec-dev-id') + '"]');
                    }
                    oSingleSelection.setProductTargetKey(oOptionGroup, 'setproduct');
                    ProductSet.procOptionBox(oOptionGroup);
                } else {
                    if (typeof(setPrice) === 'function') {
                        var sID = this.oCommon.getOptionChooseID(oOptionGroup);
                        setPrice(false, true, sID);
                    }
                }
            }
        } else {
            if (sOptionGroup.indexOf('setproduct') === -1) {
                return;
            }
            this.hookIndividualSetProductParameter(this.sOptionKey);
            if (Object.keys(ProductSet.getSetIndividualList()).length > 0) {
                TotalAddSale.getCalculatorSalePrice(ProductSet.setTotalPrice);
            }
        }
    },
    hookIndividualSetProductParameter : function(sOptionKey)
    {
        ProductSet.delOptionBoxData(sOptionKey);
        // 분리세트 상품 코드 삭제
        var oSetIndividualList = ProductSet.getSetIndividualList();
        delete oSetIndividualList[sOptionKey];
        TotalAddSale.setParam('unit_set_product_no', oSetIndividualList);

        // 할인 금액 품목 코드 삭제
        TotalAddSale.removeProductData(sOptionKey);

        // 아무 옵션이 없는 경우
        if (Object.keys(oSetIndividualList).length === 0) {
            TotalAddSale.setParam('product', oProductList);
            TotalAddSale.setTotalAddSalePrice(0);
            ProductSet.setTotalPrice();
        } else {
            var aProductNo = [];
            for (var i = 0; i < Object.keys(oSetIndividualList).length; i++) {
                var iProductNum = oSetIndividualList[Object.keys(oSetIndividualList)[i]];
                if (aProductNo.indexOf(iProductNum) === -1) {
                    aProductNo.push(iProductNum);
                }
            }
            if (aProductNo.length === 1) {
                TotalAddSale.setParam('product_no', aProductNo[0]);
                TotalAddSale.setParam('is_set', false);
            } else {
                TotalAddSale.setParam('product_no', iProductNo);
                TotalAddSale.setParam('is_set', true);
            }
        }

    },
    eachCallbackOptionTypeF : function(oOptionChoose)
    {
        if (this.oCommon.getOptionSelectedValue(oOptionChoose) === '*') {
            var oTarget = this.getDeleteTriggerElement(oOptionChoose);
            // 옵션이 실제로 취소되었음
            oTarget.click();
        } else {
            // 다른 옵션으로 변경되었음 - 삭제 액션이 아니라 삭제된거처럼 만들어야함
            var sContext = this.getDeleteTriggerContext(oOptionChoose);
            EC$(sContext).remove();
        }
        return true;
    },
    getDeleteTriggerElement : function(oOptionChoose)
    {
        var sSelector = this.getDeleteTriggerSelector(oOptionChoose);
        var sContext = this.getDeleteTriggerContext(oOptionChoose);

        return EC$(sSelector, sContext);
    },
    getTargetKey : function(oOptionChoose)
    {
        // 기본상품(옵션없는 상품, 조합형옵션 상품, 연동형 옵션 상품, 일체형 세트상품) : 상품번호
        // 독립형 옵션 상품 : 상품번호|옵션순서
        // 분리세트 상품 : 구성상품번호|세트상품번호
        // 분리세트상품의 독립형 옵션 상품 : 구성상품번호|세트상품번호|옵션순서
        var sTargetKey = this.oCommon.getOptionProductNum(oOptionChoose);
        var sOptionGroup = this.oCommon.getOptionSelectGroup(oOptionChoose);
        if (sOptionGroup.indexOf('setproduct') > -1) {
            if (sSetProductType === 'S') {
                sTargetKey =  sTargetKey + '|' + iProductNo;
            } else {
                sTargetKey = iProductNo;
            }
        }
        if (this.oCommon.getOptionType(oOptionChoose) === 'F') {
            sTargetKey = sTargetKey + '|' + this.oCommon.getOptionSortNum(oOptionChoose);
        }
        return sTargetKey;

    },
    getDeleteTriggerContext : function(oOptionChoose)
    {
        var sOptionGroup = this.oCommon.getOptionSelectGroup(oOptionChoose);
        var sContext = 'tr.add_product';
        if (sOptionGroup.indexOf('addproduct') < 0) {
            sContext = 'tr.option_product';
        }
        var sTargetKey = this.getTargetKey(oOptionChoose);
        return sContext+'[target-key="'+ sTargetKey +'"]';
    },
    getDeleteTriggerSelector : function(oOptionChoose)
    {
        var sOptionGroup = this.oCommon.getOptionSelectGroup(oOptionChoose);
        var sSelector = '.option_add_box_del';
        if (sOptionGroup.indexOf('addproduct') < 0) {
            sSelector = '.option_box_del';
        }
        return sSelector;
    }
};

var oSingleSelection = function()
{
    var sProductTargetKey = null;
    var sSingleQuantityInputSelector = 'input.single-quantity-input';
    var sIndexKey = '#PRODUCTNUM#';
    var sSingleObjectName = 'oSingleItemData['+sIndexKey+']';

    var getTotalPriceSelector = function()
    {
        return EC$('#totalProducts .total:visible').length > 0 ? '#totalProducts .total' : '.xans-product-detail #totalPrice .total, .xans-product-zoom #totalPrice .total';
    };

    var isItemSelectionTypeS = function()
    {
        return EC$(sSingleQuantityInputSelector).filter(':visible').length > 0;
    };

    var isItemSelectionTypeM = function()
    {
        return EC$(sSingleQuantityInputSelector).filter(':visible').length === 0;
    };

    var getProductNum = function(oQuantityObject)
    {
        if (EC$(oQuantityObject).hasClass('single-quantity-input') === true) {
            return parseInt(EC$(oQuantityObject).attr('product-no'), 10);
        }
        if (EC$(oQuantityObject).attr('product-no')) {
            return EC$(oQuantityObject).attr('product-no');
        }
        var sProductNumClass = EC$.grep(EC$(oQuantityObject).attr('class').split(' '), function(sClassName,i) {
            return EC_UTIL.trim(sClassName).indexOf('product-no-') === 0;
        })[0];
        return parseInt(sProductNumClass.replace('product-no-', ''), 10);
    };

    var getOptionSequenceNum = function(oQuantityObject)
    {
        if (EC$(oQuantityObject).hasClass('single-quantity-input') === true) {
            return parseInt(EC$(oQuantityObject).attr('option-sequence'), 10);
        }
        if (EC$(oQuantityObject).attr('has-option') === 'F') {
            return 1;
        }
        if (EC$(oQuantityObject).attr('option_type') === 'F' && EC$(oQuantityObject).attr('option_sort_no')) {
            return parseInt(EC$(oQuantityObject).attr('option_sort_no'), 10);
        }
        var sSequenceClass = EC$.grep(EC$(oQuantityObject).attr('class').split(' '), function(sClassName,i) {
            return EC_UTIL.trim(sClassName).indexOf('option-sequence-') === 0;
        })[0];

        return parseInt(sSequenceClass.replace('option-sequence-', ''), 10);
    };

    var setProductTargetKey = function(oElement, sType)
    {
        var iTargetProductNum = iProductNo;
        var sTargetKey = iProductNo;
        var iOptionSequence = 1;
        if (typeof(oElement) !== 'undefined') {
            if (oElement.hasClass('single-quantity-input') === true || oElement.hasClass('quantity-handle') === true) {
                iTargetProductNum = getProductNum(oElement);
                iOptionSequence = getOptionSequenceNum(oElement);
            } else {
                var oOptionChoose = EC_SHOP_FRONT_NEW_OPTION_COMMON.setOptionBoxElement(oElement);
                iTargetProductNum = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionProductNum(oOptionChoose);
                if (isNaN(iTargetProductNum) === true) {
                    iTargetProductNum = EC$(oElement).attr('product-no');
                }
                iOptionSequence = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSortNum(oOptionChoose);
            }
            sTargetKey = iTargetProductNum;
        }
        if (sType === 'setproduct') {
            if (sSetProductType === 'S') {
                sTargetKey = iTargetProductNum+'|'+iProductNo;
            }
        }
        var bAddProductOptionF = false;
        var bSetProductOptionF = false;
        if (sType === 'addproduct') {
            var oOptionChoose = EC$('select#addproduct_option_id_'+iTargetProductNum+'_'+iOptionSequence);
            bAddProductOptionF = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionType(oOptionChoose) === 'F';
        }
        if (sType === 'setproduct') {
            var oOptionChoose = EC$('select#setproduct_option_id_'+iTargetProductNum+'_'+iOptionSequence);
            bSetProductOptionF = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionType(oOptionChoose) === 'F';
        }
        if ((typeof(sType) === 'undefined' && option_type === 'F') || bSetProductOptionF === true || bAddProductOptionF === true) {
            sTargetKey = sTargetKey+'|'+iOptionSequence;
        }
        sProductTargetKey = sTargetKey;
    };

    return {
        getProductTargetKey : function()
        {
            return sProductTargetKey;
        },
        setProductTargetKey : function(oElement, sType)
        {
            return setProductTargetKey(oElement, sType);
        },
        getTotalPriceSelector : function()
        {
            return getTotalPriceSelector();
        },
        getProductNum : function(oQuantityButtnObject)
        {
            return getProductNum(oQuantityButtnObject);
        },
        getOptionSequence : function(oQuantityButtnObject)
        {
            return getOptionSequenceNum(oQuantityButtnObject);
        },
        getQuantityInput : function(oQuantityButtonObject, sContext)
        {
            var iSequenceNum = getOptionSequenceNum(oQuantityButtonObject);
            var iProductNum = getProductNum(oQuantityButtonObject);
            if (typeof(sContext) === 'undefined') {
                sContext = null;
            }

            return EC$(sSingleQuantityInputSelector+'[option-sequence='+iSequenceNum+'][product-no='+iProductNum+']', sContext);
        },
        isItemSelectionTypeS : function()
        {
            return isItemSelectionTypeS();
        },
        isItemSelectionTypeM : function()
        {
            return isItemSelectionTypeM();
        }
    };
}();
var EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET = {
    /**
     * 장바구니 담기 Ajax완료여부
     */
    bIsLoadedPriceAjax : false,

    /**
     * 옵션 선택시 장바구니 바로 담기 기능 사용 여부
     */
    bIsUseDirectBasket : false,

    /**
     * 옵션선택후 주석옵션이 선언되어있다면 바로 장바구니담기
     * @param oOptionChoose 구분할 옵션박스 object
     */
    completeCallback : function(oOptionChoose) {
        if (this.isAvailableDirectBasket(oOptionChoose) === false) {
            return;
        }

        var oInterval = setInterval(function () {
            if (EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET.bIsLoadedPriceAjax === true) {
                
                //장바구니 담기
                product_submit(2, '/exec/front/order/basket/', this);
                EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET.bIsLoadedPriceAjax = false;

                //옵션박스 제거
                EC$('.option_box_del').each(function() {
                    EC$(this).trigger('click');
                });

                //옵션선택값 초기화
                EC$('[product_option_area="' + EC$(oOptionChoose).attr('product_option_area') + '"]').each(function() {
                    EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(this, '*', true, true);
                });

                clearInterval(oInterval);
            }
        }, 300);
    },

    /**
     * 사용가능한 상태인지 확인
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @return boolean true : 사용가능, false : 사용불가
     */
    isAvailableDirectBasket : function (oOptionChoose) {

        if (this.bIsUseDirectBasket === false) {
            return false;
        }

        oOptionChoose = EC_SHOP_FRONT_NEW_OPTION_COMMON.setOptionBoxElement(oOptionChoose);
        if (EC$(oOptionChoose).attr('product_type') !== 'product_option') {
            return false;
        }

        return true;
    },

    /**
     * 옵션선택시 장바구니 바로담기 기능
     */
    setUseDirectBasket : function ()
    {
        this.bIsUseDirectBasket = true;
    }


};

var EC_SHOP_FRONT_NEW_OPTION_EXTRA_FUNDING = {
    sCurrentCompositionCode : null,
    prefetch : function(oOptionChoose)
    {
    },
    completeCallback : function(oOptionChoose)
    {
    },
    eachCallback : function(oOptionChoose)
    {
        if (typeof(EC$(oOptionChoose).attr('composition-code')) === 'undefined') {
            return true;
        }
        var sCompositionCode = EC$(oOptionChoose).attr('composition-code');
        EC$('input.selected-funding-item[composition-code="'+sCompositionCode+'"]').remove();
        this.sCurrentCompositionCode = sCompositionCode;
        var sItemCode = EC_SHOP_FRONT_NEW_OPTION_COMMON.getItemCode(oOptionChoose);
        if (sItemCode === false) {
            return true;
        }
        /*
        var oItemCode = EC$('<input>').attr({
            'type' : 'hidden',
            'composition-code' : sCompositionCode
        }).addClass('selected-funding-item option_box_id').val(sItemCode);
        EC$('.EC-funding-checkbox[value="'+sCompositionCode+'"]').append(oItemCode);
         */
    }
};
/**
 * 뉴상품 옵션 셀렉트 공통파일
 */
var EC_SHOP_FRONT_NEW_OPTION_COMMON = {
    cons : null,

    data : null,

    bind : null,

    validation : null,

    /**
     * 페이지 로드가 완료되었는지
     */
    isLoad : false,

    initObject : function() {
        this.cons = EC_SHOP_FRONT_NEW_OPTION_CONS;
        this.data = EC_SHOP_FRONT_NEW_OPTION_DATA;
        this.bind = EC_SHOP_FRONT_NEW_OPTION_BIND;
        this.validation = EC_SHOP_FRONT_NEW_OPTION_VALIDATION;
    },

    /**
     * 페이지 로딩시 초기화
     */
    init : function() {
        var oThis = this;
        //조합분리형이지만 옵션이 1개인경우
        var bIsSolidOption = false;
        //첫 로드시에는 첫번째 옵션만 검색
        EC$('select[option_select_element="ec-option-select-finder"][option_sort_no="1"], ul[option_select_element="ec-option-select-finder"][option_sort_no="1"]').each(function() {
            //연동형이 아닌고 분리형일때만 실행
            bIsSolidOption = false;
            if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isSeparateOption(this) === true) {
                if (Olnk.isLinkageType(EC$(this).attr('option_type')) === false) {
                    if (parseInt(EC$('[product_option_area="'+oThis.getOptionSelectGroup(this)+'"]').length) < 2) {
                        bIsSolidOption = true;
                    }

                    oThis.data.initializeSoldoutFlag(EC$(this));

                    oThis.setOptionText(EC$(this), bIsSolidOption);
                }
            }
        });
    },

    /**
     * 옵션상품인데 모든옵션이 판매안함+진열안함일때 예외처리
     * @param sProductOptionID 옵션 Selectbox ID
     */
    isValidOptionDisplay : function(sProductOptionID)
    {
        var iOptionCount = 0;
        EC$('select[option_select_element="ec-option-select-finder"][id^="' + sProductOptionID + '"], ul[option_select_element="ec-option-select-finder"][ec-dev-id^="' + sProductOptionID + '"]').each(function() {

            if (EC_SHOP_FRONT_NEW_OPTION_COMMON.isOptionStyleButton(this) === true) {
                iOptionCount += EC$(this).find('li').length;
            } else {
                iOptionCount += EC$(this).find('option').length - 2;
            }
        });

        return iOptionCount > 0;
    },

    /**
     * 각 옵션에대해 전체품절인지 확인후
     */
    setOptionText : function(oOptionChoose, bIsSolidOption) {
        var bIsStyleButton = this.isOptionStyleButton(oOptionChoose);
        var oTargetOption = null;
        if (bIsStyleButton === true) {
            oTargetOption = EC$(oOptionChoose).find('li');
        } else {
            oTargetOption = EC$(oOptionChoose).find('option').filter('[value!="*"][value!="**"]');
        }

        var bIsDisplaySolout = EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isSoldoutOptionDisplay();
        var iProductNum = this.getOptionProductNum(oOptionChoose);
        var oThis = this;

        EC$(oTargetOption).each(function() {
            var sValue = oThis.getOptionValue(oOptionChoose, EC$(this));
            var isSoldout = EC_SHOP_FRONT_NEW_OPTION_DATA.getSoldoutFlag(iProductNum, sValue);
            var bIsDisplay = EC_SHOP_FRONT_NEW_OPTION_DATA.getDisplayFlag(iProductNum, sValue);
            var sOptionText = oThis.getOptionText(oOptionChoose, this);

            if (bIsDisplay === false) {
                EC$(this).remove();
                return;
            }

            //조합분리형인데 옵션이 1개인경우 옵션추가금액을 세팅)
            if (bIsSolidOption === true) {
                var sItemCode = oThis.data.getItemCode(iProductNum, sValue);

                var sAddText = EC_SHOP_FRONT_NEW_OPTION_BIND.setAddText(iProductNum, sItemCode, oOptionChoose);
                if (sAddText !== '') {
                    sOptionText = sOptionText + sAddText;
                }
            }

            if (isSoldout === true) {
                //품절표시안함일때 안보여주도록함(첫번째옵션이라서.. 어쩔수없이 여기서 ㅋ)
                //두번째옵션부터는 동적생성이니깐 bind에서처리
                if (bIsDisplaySolout === false) {
                    EC$(this).remove();
                    return;
                }
                //해당 옵션값 객첵가 넘어오면 바로 적용
                if (bIsStyleButton === true) {
                    EC$(this).addClass(EC_SHOP_FRONT_NEW_OPTION_CONS.BUTTON_OPTION_SOLDOUT_CLASS);
                }

                //분리형이면서 전체상품이 품절이면
                if (bIsSolidOption !== true) {
                    var sSoldoutText = EC_SHOP_FRONT_NEW_OPTION_COMMON.getSoldoutText(oOptionChoose, sValue);
                    sOptionText = sOptionText +  ' ' + sSoldoutText;

                }
            }

            oThis.setText(this, sOptionText);

        });
    },

    /**
     * 품목이 아닌 각 옵션별로 전체품절인지 황니후 품절이면 품절문구 반환
     * @param oOptionChoose
     * @param sValue
     * @returns {String}
     */
    getSoldoutText : function(oOptionChoose, sValue) {
        var sText = '';

        var iProductNum = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionProductNum(oOptionChoose);

        if (EC_SHOP_FRONT_NEW_OPTION_DATA.getSoldoutFlag(iProductNum, sValue) === true) {
            return '[' + EC_SHOP_FRONT_NEW_OPTION_EXTRA_SOLDOUT.getSoldoutDiplayText(iProductNum) + ']';
        }

        return sText;
    },

    /**
     * 셀렉트박스형 옵션인지 버튼형 옵션이지 확인
     * @param oOptionChoose 구분할 옵션박스 object
     * @returns true => 버튼형옵션, false => 기존 select형 옵션
     */
    isOptionStyleButton : function(oOptionChoose) {
        var sOptionStyle = EC$(oOptionChoose).attr(this.cons.OPTION_STYLE);
        if (sOptionStyle === 'preview' || sOptionStyle === 'button' || sOptionStyle === 'radio') {
            return true;
        }

        return false;
    },

    /**
     * 해당 옵션의 옵션출력타입(분리형 : S, 일체형 : C)
     * @param oOptionChoose 구분할 옵션박스 object
     * @returns 옵션타입
     */
    getOptionListingType : function(oOptionChoose)
    {
        oOptionChoose = this.setOptionBoxElement(oOptionChoose);
        return EC$(oOptionChoose).attr(this.cons.OPTION_LISTING_TYPE);
    },

    /**
     * 해당 옵션의 옵션타입(조합형 : T, 연동형 : E, 독립형 : F)
     * @param oOptionChoose 구분할 옵션박스 object
     * @returns 옵션타입
     */
    getOptionType : function(oOptionChoose) {
        oOptionChoose = this.setOptionBoxElement(oOptionChoose);
        return EC$(oOptionChoose).attr(this.cons.OPTION_TYPE);
    },

    /**
     * 해당 옵션의 옵션그룹명을 가져온다
     * @param oOptionChoose 구분할 옵션박스 object
     * @returns 옵션그룹이름
     */
    getOptionSelectGroup : function(oOptionChoose) {
        return EC$(oOptionChoose).attr(this.cons.GROUP_ATTR_NAME);
    },

    /**
     * sOptionStyleConfirm 에 해당하는 옵션인지 확인
     * @param oOptionChoose 구분할 옵션박스 object
     * @param sOptionStyleConfirm 옵션스타일(EC_SHOP_FRONT_NEW_OPTION_CONS : OPTION_STYLE_PREVIEW 또는 OPTION_STYLE_BUTTON)
     * @return boolean 확인결과
     */
    isOptionStyle : function(oOptionChoose, sOptionStyleConfirm) {
        var sOptionStype = EC$(oOptionChoose).attr(this.cons.OPTION_STYLE);
        if (sOptionStype === sOptionStyleConfirm) {
            return true;
        }

        return false;
    },

    /**
     * 해당 옵션의 선택된 Text내용을 가져옴
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns 옵션 내용Text
     */
    getOptionSelectedText : function(oOptionChoose) {
        if (this.isOptionStyleButton(oOptionChoose) === true) {
            return EC$(oOptionChoose).find('li.' + this.cons.BUTTON_OPTION_SELECTED_CLASS).attr('title');
        } else {
            return EC$(oOptionChoose).find('option:selected').text();
        }
    },

    /**
     * 해당 옵션의 선택된 값을 가져옴
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns string 옵션값
     */
    getOptionSelectedValue : function(oOptionChoose) {
        oOptionChoose = this.setOptionBoxElement(oOptionChoose);

        if (this.isOptionStyleButton(oOptionChoose) === true) {
            var oTarget = EC$(oOptionChoose).find('li.' + this.cons.BUTTON_OPTION_SELECTED_CLASS);

            //버튼형옵션은 *, **값이 없기떄문에 선택된게 없다면 강제리턴
            if (oTarget.length < 1) {
                return '*';
            } else {
                return oTarget.attr('option_value');
            }
        } else {
            var sValue = EC$(oOptionChoose).val();
            return (EC_UTIL.trim(sValue) === '') ? '*' : sValue;
        }
    },

    /**
     * 해당 Element의 값을 가져옴
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @param oOptionChooseElement 값을 가져오려는 옵션 항목
     * @returns string 옵션값
     */
    getOptionValue : function(oOptionChoose, oOptionChooseElement) {
        if (this.isOptionStyleButton(oOptionChoose) === true) {
            return EC$(oOptionChooseElement).attr('option_value');
        } else {
            return EC$(oOptionChooseElement).val();
        }
    },

    /**
     * 해당 Element의 Text값을 가져옴
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @param oOptionChooseElement 값을 가져오려는 옵션 항목
     * @returns string 옵션값
     */
    getOptionText : function(oOptionChoose, oOptionChooseElement) {
        if (this.isOptionStyleButton(oOptionChoose) === true) {
            return EC$(oOptionChooseElement).attr('title');
        } else {
            return EC$(oOptionChooseElement).text();
        }
    },

    /**
     * 선택된 옵션의 Element를 가져온다
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns 선택옵션의 DOM Element
     */
    getOptionSelectedElement : function(oOptionChoose) {
        if (this.isOptionStyleButton(oOptionChoose) === true) {
            return EC$(oOptionChoose).find('li.' + this.cons.BUTTON_OPTION_SELECTED_CLASS);
        } else {
            return EC$(oOptionChoose).find('option:selected');
        }
    },

    getOptionLastSelectedElement : function(sOptionGroup)
    {
        var oOptionGroup = this.getGroupOptionObject(sOptionGroup);
        var aTempResult = [];
        oOptionGroup.each(function(i) {
            if (EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectedValue(oOptionGroup[i]) !== '*') {
                aTempResult.push(oOptionGroup[i]);
            }
        });
        return EC$(aTempResult[aTempResult.length - 1]);
    },

    /**
     * 해당 옵션의 상품번호를 가져옴
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns int 상품번호
     */
    getOptionProductNum : function(oOptionChoose) {
        return parseInt(EC$(oOptionChoose).attr(this.cons.OPTION_PRODUCT_NUM));
    },

    /**
     * 해당 옵션의 순번을 가져옴
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns int 해당 옵션의 순서 번호
     */
    getOptionSortNum : function(oOptionChoose) {
        oOptionChoose = this.setOptionBoxElement(oOptionChoose);
        return parseInt(EC$(oOptionChoose).attr(this.cons.OPTION_SORT_NUM));
    },

    /**
     * 이벤트 옵션까지에대해 현재까지 선택된 옵션값 배열
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @param bIsString 값이 true이면 선택된 옵션들을 구분자로 join해서 받아온다
     * @returns 현재까지 선택된 옵션값 배열
     */
    getAllSelectedValue : function(oOptionChoose, bIsString) {
        var iOptionSortNum = this.getOptionSortNum(oOptionChoose);

        //지금까지 선택된 옵션의 값
        var aSelectedValue = [];
        EC$('[product_option_area="'+EC$(oOptionChoose).attr(this.cons.GROUP_ATTR_NAME)+'"]').each(function() {
            if (parseInt(EC$(this).attr('option_sort_no')) <= iOptionSortNum) {
                aSelectedValue.push(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectedValue(EC$(this)));
            }
        });

        return (bIsString === true) ? aSelectedValue.join(this.cons.OPTION_GLUE) : aSelectedValue;
    },

    /**
     * iSelectedOptionSortNum 의 하위옵션을 초기화(0일때는 모두초기화)ㅅ
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @param iSelectedOptionSortNum 하위옵션을 초기화할 대상 옵션 순번
     */
    setInitializeDefault : function(oOptionChoose, iSelectedOptionSortNum) {
        var sOptionGroup = EC$(oOptionChoose).attr(this.cons.GROUP_ATTR_NAME);
        var iProductNum = this.getOptionProductNum(oOptionChoose);
        this.bind.setInitializeDefault(sOptionGroup, iSelectedOptionSortNum, iProductNum);
    },

    /**
     * 외부에서 기존스크립트가 호출할때는 버튼형옵션객체가 아니라 숨겨진 셀렉트박스에서 호출하므로 버튼형옵션객체를 찾아서 리턴
     */
    setOptionBoxElement : function(oOptionChoose) {
        if (typeof(EC$(oOptionChoose).attr('product_option_area_select')) !== 'undefined') {
            oOptionChoose = EC$('ul[product_option_area="'+EC$(oOptionChoose).attr('product_option_area_select')+'"][ec-dev-id="'+EC$(oOptionChoose).attr('id')+'"]');
        }

        return oOptionChoose;
    },

    /**
     * 선택한 옵션 하위옵션 모두 초기화(추가구성상품에서 연동형옵션때문에...)
     * @param oOptionChoose
     */
    setAllClear : function(oOptionChoose) {
        oOptionChoose = this.setOptionBoxElement(oOptionChoose);

        var iSortNo = parseInt(this.getOptionSortNum(oOptionChoose));
        EC$(this.getGroupOptionObject(this.getOptionSelectGroup(oOptionChoose))).each(function() {
            if (iSortNo < parseInt(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSortNum(EC$(this)))) {
                EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(EC$(this), '*');
            }
        });
    },

    /**
     * 멀티옵션(구스킨)에서 사용할때 해당 옵션의 id값을 바꾸는기능이 있어서 추가
     * @param oOptionChooseOrg
     * @param sId
     */
    setID : function(oOptionChooseOrg, sId) {
        if (EC$(oOptionChooseOrg).attr('option_style') === 'select') {
            oOptionChoose = oOptionChooseOrg;
        } else {
            oOptionChoose = EC$(oOptionChooseOrg).parent().find('ul[option_style="preview"], [option_style="button"], [option_style="radio"]');
        }

        if (this.isOptionStyleButton(oOptionChoose) === true) {
            EC$(oOptionChoose).attr('ec-dev-id', sId);
            EC$(oOptionChooseOrg).attr('id', sId);
        } else {
            EC$(oOptionChoose).attr('id', sId);
        }
    },

    /**
     * 멀티옵션(구스킨)에서 사용할때 해당 옵션의 id값을 바꾸는기능이 있어서 추가
     * @param oOptionChooseOrg
     * @param sGroupID
     */
    setGroupArea : function(oOptionChooseOrg, sGroupID) {
        var oOptionChoose = null;
        if (EC$(oOptionChooseOrg).attr('option_style') === 'select') {
            oOptionChoose = oOptionChooseOrg;
        } else {
            oOptionChoose = EC$(oOptionChooseOrg).parent().find('ul[option_style="preview"], [option_style="button"], [option_style="radio"]');
        }

        if (this.isOptionStyleButton(oOptionChoose) === true) {
            EC$(oOptionChoose).attr('product_option_area', sGroupID);
            EC$(oOptionChooseOrg).attr('product_option_area_select', sGroupID);
        } else {
            EC$(oOptionChoose).attr('product_option_area', sGroupID);
        }
    },

    /**
     * 해당 선택한 옵션의 text값을 세팅
     */
    setText : function(oSelectecOptionChoose, sText) {
        oOptionChoose = this.setOptionBoxElement(EC$(oSelectecOptionChoose).parent());

        if (this.isOptionStyleButton(oOptionChoose) === true) {
            var sValue = EC$(oSelectecOptionChoose).attr('option_value');
            var oTarget = EC$(oOptionChoose).find('li[option_value="'+sValue+'"]');
            EC$(oTarget).attr('title', sText);

        }

        if (this.isOptionStyleButton(EC$(oSelectecOptionChoose).parent()) !== true) {
            EC$(oSelectecOptionChoose).text(sText);
        }
    },

    /**
     * 추가 이미지에서 추출한 품목 코드를 바탕으로 옵션 선택
     * @param sItemCode 품목 코드
     */
    setValueByAddImage : function(sItemCode) {
        if (typeof(sItemCode) === 'undefined') {
            return;
        }

        this.selectItemCode('product_option_' + iProductNo + '_0', sItemCode);
    },

    /**
     * 외부에서 옵션을 선택하는걸 호출할 경우 해당 옵션의 product_option_area값과 품목코드를 전달
     * @param sOptionArea 옵션 element의 product_option_area값 attribute값
     * @param sItemCode 품목코드
     */
    selectItemCode : function(sOptionArea, sItemCode)
    {
        var oSelect = EC$('[product_option_area="' + sOptionArea + '"]');
        oSelect = this.setOptionBoxElement(oSelect);

        var sOptionListType = this.getOptionListingType(oSelect);
        var sOptionType = this.getOptionType(oSelect);

        //조합일체형이나 독립형인경우
        if (sOptionListType === 'C' || sOptionType === 'F') {
            this.setValue(oSelect, sItemCode, true, true);
        } else {
            var iProductNo = this.getOptionProductNum(oSelect);
            var oItemData = this.getProductStockData(iProductNo);

            if (oItemData === null) {
                return;
            }

            if (oItemData.hasOwnProperty(sItemCode) === false) {
                return;
            }

            var aOptionValue = oItemData[sItemCode].option_value_orginal;

            oSelect.each(function (i) {
                EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(this, aOptionValue[i], true, true);
            });
        }
    },

    /**
     * 해당 Element의 값을 강제로 지정
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @param sValue set 하려는 value
     * @param bIsInitialize false인 경우에는 클릭이벤트를 발생하지 않도록 한다
     * @param bChange change 이벤트 발생 여부
     */
    setValue : function(oOptionChoose, sValue, bIsInitialize, bChange) {
        // 값 세팅시 각 페이지에서 EC$(this).val()로 값을 지정할경우
        // 본래 버튼형 옵션이면 타겟을 버튼형 옵션으로 이어준다
        oOptionChoose = this.setOptionBoxElement(oOptionChoose);

        if (this.isOptionStyleButton(oOptionChoose) === true) {
            //옵션이 선택되어있는상태면 초기화후 선택
            if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isOptionSelected(oOptionChoose) === true) {
                EC$(oOptionChoose).find('li.' + this.cons.BUTTON_OPTION_SELECTED_CLASS).trigger('click');
            }

            var oTarget = EC$(oOptionChoose).find('li[option_value="' + sValue + '"]');

            if (EC$(oTarget).length > 0) {
                EC$(oTarget).trigger('click');
            } else {
                if (bIsInitialize !== false) {
                    // 선택값이 없다면 셀렉트박스 초기화
                    var iProductNum = this.getOptionProductNum(oOptionChoose);
                    var iSelectedOptionSortNum = this.getOptionSortNum(oOptionChoose);
                    var sOptionGroup = this.getOptionSelectGroup(oOptionChoose);
                    var bIsRequired = EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isRequireOption(oOptionChoose);

                    if (EC_SHOP_FRONT_NEW_OPTION_BIND.isEnabledOptionInit(oOptionChoose) === true) {
                        EC_SHOP_FRONT_NEW_OPTION_BIND.setInitializeDefault(sOptionGroup, iSelectedOptionSortNum, iProductNum, bIsRequired);
                    }

                    EC_SHOP_FRONT_NEW_OPTION_EXTRA_DISPLAYITEM.eachCallback(oOptionChoose);
                    EC_SHOP_FRONT_NEW_OPTION_BIND.setRadioButtonSelect(oTarget, oOptionChoose, false);
                }

                this.setTriggerSelectbox(oOptionChoose, sValue);
            }
        } else {
            EC$(oOptionChoose).val(sValue);

            if (typeof(bChange) !== 'undefined') {
                EC$(oOptionChoose).trigger('change');
            }
        }
    },

    /**
     * 버튼 또는 이미지형 옵션일 경우 동적 selectbox와 동기화 시킴
     * @param oOptionChoose 선택한 옵션 Object
     * @param sValue set 하려는 value
     * @param bIsTrigger 셀렉트박스의 change 이벤트를 발생시키지 않을때(ex:모바일의 옵션선택 레이어..)
     */
    setTriggerSelectbox : function(oOptionChoose, sValue, bIsTrigger)
    {
        if (this.isOptionStyleButton(oOptionChoose) === true) {
            var oTargetSelect = EC$('select[product_option_area_select="' + EC$(oOptionChoose).attr('product_option_area') + '"][id="' + EC$(oOptionChoose).attr('ec-dev-id') + '"]');
            var bChange = true;

            var sText = '';
            if (this.validation.isItemCode(sValue) === false) {
                sValue = '*';
                sText = 'empty';

                bChange = false;
            } else {
                sValue = this.getOptionSelectedValue(oOptionChoose);
                sText = this.getOptionSelectedText(oOptionChoose);
            }

            if (sValue !== '*') {
                EC$(oTargetSelect).find('option[value="' + sValue + '"]').remove('option');

                var sOptionsHtml = this.cons.OPTION_STYLE_SELECT_HTML.replace('[value]', sValue).replace('[text]', sText);

                EC$(oTargetSelect).append(EC$(sOptionsHtml));
            }

            EC$(oTargetSelect).val(sValue);

            if (bChange === true && bIsTrigger !== false) {
                EC$(oTargetSelect).trigger('change');
            }
        }
    },

    /**
     * 해당 상품의 옵션 재고 관련 데이터를 리턴
     * @param iProductNum 상품번호
     * @returns option_stock_data 데이터
     */
    getProductStockData : function(iProductNum) {
        return this.data.getProductStockData(iProductNum);
    },

    /**
     * 선택상품의 아이템코드를 반환(선택이 안되어있다면 false)
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns 아이템 코드 OR false
     */
    getItemCode : function(oOptionChoose) {
        //분리조합형일경우
        if (this.validation.isSeparateOption(oOptionChoose) === true) {
            var sSelectedValue = this.getAllSelectedValue(oOptionChoose, true);
            var iProductNum = this.getOptionProductNum(oOptionChoose);
            return this.data.getItemCode(iProductNum, sSelectedValue);
        }

        //그외의 경우에는 현재 선택된 옵션의 value가 아이템코드
        var sItemCode = this.getOptionSelectedValue(oOptionChoose);

        return (this.validation.isItemCode(sItemCode) === true) ? sItemCode : false;
    },

    /**
     * 해당 그룹내의 모든옵션에대해 선택된 품목코드를 반환
     * @param sOptionGroup 옵션 그룹 (@see : EC_SHOP_FRONT_NEW_OPTION_GROUP_CONS)
     * @param bIsAbleSoldout 품절품목에 대한 아이템코드도 포함
     * @returns array 선택된 아이템코드 배열
     */
    getGroupItemCodes : function(sOptionGroup, bIsAbleSoldout) {
        var aItemCode = [];
        var sItemCode = '';
        var oTarget = EC$('[' + this.cons.GROUP_ATTR_NAME + '^="' + sOptionGroup + '"]');

        //뉴스킨인 경우에는 옵션박스 레이어에 생성된 input에서 가져온다
        if (isNewProductSkin() === true) {
            EC$('.' + EC_SHOP_FRONT_NEW_OPTION_GROUP_CONS.DETAIL_OPTION_BOX_PREFIX).each(function() {
                //옵션박스에 생성된 input태그이므로 val()로 가져온다
                sItemCode = EC$(this).val();
                if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isItemCode(sItemCode) === true) {
                    aItemCode.push(sItemCode);
                }
            });

            //품절품목에 대한 아이템코드도 포함시킨다 - 현재는 관심상품담을경우에 쓰이는것으로 보임
            if (bIsAbleSoldout === true) {
                EC$('.' + EC_SHOP_FRONT_NEW_OPTION_GROUP_CONS.DETAIL_OPTION_BOX_SOLDOUT_PREFIX).each(function() {
                    aItemCode.push(EC$(this).val());

                    if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isItemCode(sItemCode) === true) {
                        aItemCode.push(sItemCode);
                    }
                });
            }
        } else {
            //구스킨인 경우에는 해당하는 옵션에 선택된 값만 가져옴
            EC$(oTarget).each(function() {
                sItemCode = EC_SHOP_FRONT_NEW_OPTION_COMMON.getItemCode(this);

                //이미 저장된 아이템코드이면 제와(분리형인경우 같은 값이 여러개 들어올수있음)
                //조합형을 따로 처리하기보다는 그냥 두는게 더 간단하다는 핑계임
                if (EC$.inArray(sItemCode, aItemCode) > -1) {
                    return true;//continue
                }

                if (sItemCode !== false) {
                    aItemCode.push(sItemCode);
                }
            });
        }

        return aItemCode;
    },

    /**
     * 해당 품목의 품절 여부
     * @param iProductNum 상품번호
     * @param sItemCode 품목코드
     * @returns boolean 품절여부
     */
    isSoldout : function(iProductNum, sItemCode) {
        var aStockData = this.getProductStockData(iProductNum);

        if (typeof(aStockData[sItemCode]) === 'undefined') {
            return false;
        }

        //재고를 사용하고 재고수량이 1개미만이면 품절
        if (aStockData[sItemCode].use_stock ===  true && parseInt(aStockData[sItemCode].stock_number) < 1) {
            return true;
        }

        //판매안함 상태이면 품절
        if (aStockData[sItemCode].is_selling === 'F') {
            return true;
        }

        return false;
    },

    /**
     * 진열여부 확인
     */
    isDisplay : function(iProductNum, sItemCode) {
        var aStockData = this.getProductStockData(iProductNum);

        if (typeof(aStockData[sItemCode]) === 'undefined') {
            return false;
        }

        if (aStockData[sItemCode].is_display !== 'T') {
            return false;
        }

        return true;
    },

    /**
     * sOptionGroup에 해당하는 옵션셀렉트박스의 Element를 가져온다
     * @param sOptionGroup sOptionGroup 옵션 그룹 (@see : EC_SHOP_FRONT_NEW_OPTION_GROUP_CONS)
     * @returns 해당 옵션셀렉트박스 Element전체
     */
    getGroupOptionObject : function(sOptionGroup) {
        return EC$('[' + this.cons.GROUP_ATTR_NAME + '^="' + sOptionGroup + '"]');
    },

    /**
     * 해당 옵션그룹에서 필수옵션의 갯수를 가져온다
     * @param sOptionGroup sOptionGroup 옵션 그룹 (@see : EC_SHOP_FRONT_NEW_OPTION_GROUP_CONS)
     * @returns 필수옵션 갯수
     */
    getRequiredOption : function(sOptionGroup) {
        return this.getGroupOptionObject(sOptionGroup).filter('[required="true"],[required="required"]');
    },

    /**
     * 해당 옵션의 전체 Value값을 가져옴(옵션그룹이 아니라 단일 옵션 셀렉츠박스)
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns {Array}
     */
    getAllOptionValues : function(oOptionChoose) {
        //일반 셀렉트박스일때
        var aOptionValue = [];
        if (this.isOptionStyleButton(oOptionChoose) === false) {
            EC$(oOptionChoose).find('option[value!="*"][value!="**"]').each(function() {
                aOptionValue.push(EC$(this).val());
            });
        } else {
            //버튼형 옵션일경우
            EC$(oOptionChoose).find('li[option_value!="*"][option_value!="**"]').each(function() {
                aOptionValue.push(EC$(this).attr('option_value'));
            });
        }

        return aOptionValue;
    },

    /**
     * 해당 옵션의 실제 id값을 리턴
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     * @returns {String}
     */
    getOptionChooseID : function(oOptionChoose) {
        var sID = '';
        if (this.isOptionStyleButton(oOptionChoose) === true) {
            sID = EC$(oOptionChoose).attr('ec-dev-id');
        } else {
            sID = EC$(oOptionChoose).attr('id');
        }

        return sID;
    }
};

EC$(function() {
    EC_SHOP_FRONT_NEW_OPTION_COMMON.isLoad = true;

    //표시된 옵션 선택박스에 대해  디폴트 옵션데이터 정리
    EC_SHOP_FRONT_NEW_OPTION_DATA.setDefaultData();

    EC_SHOP_FRONT_NEW_OPTION_COMMON.init();
});

/**
 * 옵션에대한 Attribute 및 구분자 모음
 */
var EC_SHOP_FRONT_NEW_OPTION_CONS = {
    /**
     * 옵션 그룹 Attribute Key(각 상품 및 영역별 구분을 위한 값)
     */
    GROUP_ATTR_NAME : 'product_option_area',

    /**
     * 옵션 스타일 Attribute Key
     */
    OPTION_STYLE : 'option_style',

    /**
     * 상품번호 Attribute Key
     */
    OPTION_PRODUCT_NUM : 'option_product_no',

    /**
     * 각 옵션의 옵션순서 Attribute Key
     */
    OPTION_SORT_NUM : 'option_sort_no',

    /**
     * 옵션 타입 Attribute Key
     */
    OPTION_TYPE : 'option_type',

    /**
     * 옵션 출력 타입 Attribute Key
     */
    OPTION_LISTING_TYPE : 'item_listing_type',

    /**
     * 옵션 값 구분자
     */
    OPTION_GLUE : '#$%',

    /**
     * 미리보기형 옵션
     */
    OPTION_STYLE_PREVIEW : 'preview',

    /**
     * 버튼형 옵션
     */
    OPTION_STYLE_BUTTON : 'button',

    /**
     * 기존 셀렉트박스형 옵션
     */
    OPTION_STYLE_SELECT : 'select',

    /**
     * 라디오박스형 옵션
     */
    OPTION_STYLE_RADIO : 'radio',

    /**
     * 각 옵션마다 연결된 이미지 Attribute
     */
    OPTION_LINK_IMAGE : 'link_image',

    /**
     * 셀렉트박스형 옵션의 Template
     */
    OPTION_STYLE_SELECT_HTML : '<option value="[value]">[text]</option>',

    /**
     * 기본 품절 문구
     */
    OPTION_SOLDOUT_DEFAULT_TEXT : __("품절"),

    /**
     * 버튼형 옵션의 품절표시 class
     */
    BUTTON_OPTION_SOLDOUT_CLASS : 'ec-product-soldout',

    /**
     * 버튼형 옵션의 선택불가 class
     */
    BUTTON_OPTION_DISABLE_CLASS : 'ec-product-disabled',

    /**
     * 버튼형 옵션의 선택된 옵션값을 구분하기위한 상수
     */
    BUTTON_OPTION_SELECTED_CLASS : 'ec-product-selected'
};

/**
 * 각 옵션그룹에 대한 Key 정의
 */
var EC_SHOP_FRONT_NEW_OPTION_GROUP_CONS = {
    /**
     * 상품디테일의 메인 옵션 그룹
     */
    DETAIL_OPTION_GROUP_ID : 'product_option_',

    /**
     * 뉴스킨 상품상세의 옵션선택시 쩔어지는 옵션박스레이어 class명
     */
    DETAIL_OPTION_BOX_PREFIX : 'option_box_id',

    /**
     * 뉴스킨 상품상세의 옵션선택시 쩔어지는 옵션박스레이어 class명(품절일경우의 prefix)
     * Prefix존누 많음
     */
    DETAIL_OPTION_BOX_SOLDOUT_PREFIX : 'soldout_option_box_id'
};

var EC_SHOP_FRONT_NEW_OPTION_BIND = {

    /**
     * 선택한 옵션 그룹(product_option_상품번호 : 상품상세일반상품)
     */
    sOptionGroup : null,

    /**
     * 옵션이 모두 선택되었을때 해당하는 item_code를 Set
     */
    sItemCode : false,

    /**
     * 선택한 옵션의 상품번호
     */
    iProductNum : 0,

    /**
     * 선택한 옵션의 순번
     */
    iOptionIndex : null,

    /**
     * 선택한 옵션의 옵션 스타일(select : 셀렉트박스, preview : 미리보기, button : 버튼형)
     */
    sOptionStyle : null,

    /**
     * 해당 상품 옵션 갯수
     */
    iOptionCount : 0,

    /**
     * 품절옵션 표시여부
     */
    bIsDisplaySolout : true,

    /**
     * 선택한 옵션의 객체(셀렉트박스 또는 버튼형 옵션 박스(ul태그))
     */
    oOptionObject : null,

    /**
     * 선택한 옵션의 다음옵션 Element
     */
    oNextOptionTarget : null,

    /**
     * 선택된 옵션 값
     */
    aOptionValue : [],

    /**
     * 옵션텍스트에 추가될 항목에대한 정의
     */
    aExtraOptionText : [
        EC_SHOP_FRONT_NEW_OPTION_EXTRA_PRICE,
        EC_SHOP_FRONT_NEW_OPTION_EXTRA_SOLDOUT,
        EC_SHOP_FRONT_NEW_OPTION_EXTRA_IMAGE,
        EC_SHOP_FRONT_NEW_OPTION_EXTRA_DISPLAYITEM,
        EC_SHOP_FRONT_NEW_OPTION_EXTRA_ITEMSELECTION,
        EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET,
        EC_SHOP_FRONT_NEW_OPTION_EXTRA_FUNDING
    ],

    /**
     * EC_SHOP_FRONT_NEW_OPTION_CONS 객체 Alias
     */
    cons : null,

    /**
     * EC_SHOP_FRONT_NEW_OPTION_COMMON 객체 Alias
     */
    common : null,

    /**
     * EC_SHOP_FRONT_NEW_OPTION_DATA 객체 Alias
     */
    data : null,

    /**
     * EC_SHOP_FRONT_NEW_OPTION_VALIDATION 객체 Alias
     */
    validation : null,

    isEnabledOptionInit : function(oOptionChoose)
    {
        var iProductNum = EC$(oOptionChoose).attr('option_product_no');
        //연동형이면서 옵션추가버튼설정이면 순차로딩제외
        if (Olnk.isLinkageType(this.common.getOptionType(oOptionChoose)) === true && (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isUseOlnkButton() === true || EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isBindUseOlnkButton(iProductNum) === true)) {
            return false;
        }

        if (this.common.getOptionType(oOptionChoose) === 'F') {
            return false;
        }

        return true;
    },

    /**
     * 각 옵션값에 대한 이벤트 처리
     * @param oThis 옵션 셀렉트박스 또는 버튼박스
     * @param oSelectedElement 선택한 옵션값
     * @param bIsUnset true 이명 deselected된상태로 초기화(setValue를 통해서 틀어왔을떄만 값이 있음)
     */
    initialize : function(oThis, oSelectedElement, bIsUnset)
    {
        this.sItemCode = false;
        this.oOptionObject = oThis;

        // 실제 옵션 처리전에 처리해야할 내용을 모아 놓는다
        this.prefetch(oThis);

        if (oSelectedElement !== null) {
            if (EC$(oSelectedElement).hasClass(EC_SHOP_FRONT_NEW_OPTION_CONS.BUTTON_OPTION_DISABLE_CLASS) === true) {
                this.setRadioButtonSelect(oSelectedElement, oThis, false);
                return;
            }

            //선택 옵션에대한 disable처리나 활성화 처리
            this.setSelectButton(oSelectedElement, bIsUnset);

            //필수정보 Set
            this.setSelectedOptionConf();

            //연동형이면서 옵션추가버튼설정이면 순차로딩제외..
            if (this.isEnabledOptionInit(this.oOptionObject) === true) {
                var bIsDelete = true;
                var bIsRequired = EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isRequireOption(this.oOptionObject);
                //해당 옵션이 연동형이면서 선택형 옵션이면 하위 옵션은 값만 초기화
                if (Olnk.isLinkageType(this.common.getOptionType(this.oOptionObject)) === true &&  bIsRequired=== false) {
                    bIsDelete = false;
                }

                //선택한 옵션이 옵션이 아닐경우 하위옵션 초기화
                //선택한 옵션이 옵션이 아니면 아래 로직은 타지 않고 eachCallback은 실행함
                this.setInitializeDefault(this.sOptionGroup, this.iOptionIndex, this.iProductNum, bIsRequired);

                if (bIsDelete === true && EC$(oSelectedElement).hasClass(this.cons.BUTTON_OPTION_DISABLE_CLASS) === false && this.validation.isOptionSelected(this.oOptionObject) === true) {
                    //선택한 옵션의 다음옵션값을 Parse
                    //연동형일경우에는 제외 / 조합분리형만 처리되도록 함
                    if (Olnk.isLinkageType(this.sOptionType) === false && this.validation.isSeparateOption(this.oOptionObject) === true) {
                        this.data.initializeOptionValue(this.oOptionObject);
                    }

                    //각 옵션을 초기화및 옵션 리스트 HTML생성
                    //조합분리형일때만 처리
                    if (this.validation.isSeparateOption(this.oOptionObject) === true) {
                        this.setOptionHTML();
                    }
                }
            }

            //해당 값이 true나 false이면 setValue를 통해서 들어온것이기때문에 다시 실행할 필요 없음
            //if (typeof(bIsUnset) === 'undefined') {
                //셀렉트박스 동기화
                this.common.setTriggerSelectbox(this.oOptionObject, this.common.getOptionSelectedValue(this.oOptionObject));
            //}

            //옵션이 모두 선택되었다면 아이템코드를 세팅
            this.setItemCode();
        }

        //옵션선택이 끝나면 각 옵션마다 처리할 프로세스(각 추가기능에서)
        this.eachCallback(oThis);

        //모든 옵션이 선택되었다면
        if (this.sItemCode !== false) {

            var sID = this.common.getOptionChooseID(this.oOptionObject);

            //상세 메인 상품에서만 실행되도록 예외처리
            if (typeof(setPrice) === 'function' && /^product_option_id+/.test(sID) === true) {
                setPrice(false, true, sID);
            }

            //모든 옵션선택이 끝나면 처리할 프로세스(각 추가기능에서)
            this.completeCallback(oThis);
        }
    },

    /**
     * 실제 옵션의 선택여부를 해제하기전 실행하는 액션
     */
    prefetch : function(oThis)
    {
        EC$(this.aExtraOptionText).each(function() {
            if (typeof(this.prefetech) === 'function') {
                this.prefetech(oThis);
            }
        });
    },

    /**
     * 각 옵션 선택시마다 처리할 Callback(Extra에 있는 추가기능)
     */
    eachCallback : function(oThis)
    {
        EC$(this.aExtraOptionText).each(function() {
            if (typeof(this.eachCallback) === 'function') {
                this.eachCallback(oThis);
            }
        });
    },

    /**
     * 옵션선택을 하고 품목이 정해졌을때 Callback(Extra에 있는 추가기능)
     */
    completeCallback : function(oThis)
    {
        EC$(this.aExtraOptionText).each(function() {
            if (typeof(this.completeCallback) === 'function') {
                this.completeCallback(oThis);
            }
        });
    },

    /**
     * iSelectedOptionSortNum보다 하위 옵션들을 초기상태로 변경함
     * @param sOptionGroup 옵션선택박스 그룹
     * @param iSelectedOptionSortNum 하위옵션을 초기화할 대상 옵션 순번
     * @param iProductNum 상품번호
     * @param bIsSetValue COMMON.setValue에서 호출시에는 다시 setValue를 하지 않는다
     */
    setInitializeDefault : function(sOptionGroup, iSelectedOptionSortNum, iProductNum, bSelectedOptionRequired) {
        var iSortNum = 0;
        var sHtml = '';
        var bIsDelete = null;

        EC$('['+this.cons.GROUP_ATTR_NAME+'="'+sOptionGroup+'"]').each(function() {

            iSortNum = parseInt(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSortNum(this));

            //선택한 옵션의 하위옵션들을 초기화
            if (iSelectedOptionSortNum < iSortNum) {

                var bIsRequired = EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isRequireOption(this);

                //선택했던 옵션이 연동형이면서 선택형 옵션이면 값만 초기화
                //bIsDelete = (bIsDelete = null && isOlnk === true && bSelectedOptionRequired === true && bIsRequired === false) ? false : true;
                if (bIsDelete === null) {
                    //선택했던 옵션이 선택형 옵션이면 처리하지 않음
                    if (bSelectedOptionRequired === false) {
                        bIsDelete = false;
                    } else if (bSelectedOptionRequired === true) {//선택했던 옵션이 필수옵션이면 진행
                        //선택했던 옵션이 필수이면서 현재 옵션이 필수이면 초기화
                        if (bIsRequired === true) {
                            bIsDelete = true;
                        } else {
                            //선택했던 옵션이 필수이면서 현재옵션이 선택형옵션이면 다음옵션에서 체크
                            bIsDelete = null;
                        }
                    }
                }

                if (bIsDelete === true) {
                    sHtml = EC_SHOP_FRONT_NEW_OPTION_DATA.getDefaultOptionHTML(iProductNum, iSortNum);
                    EC$(this).html('');
                    EC$(this).append(sHtml);
                }

                //셀렉트박스이면서 필수옵션이라면 기본값을 제외하고 option삭제
                if (EC_SHOP_FRONT_NEW_OPTION_COMMON.isOptionStyle(this, EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_STYLE_SELECT) === true) {

                    if (bIsDelete === true && bIsRequired === true) {
                        EC$(this).find('option').prop('disabled', false);
                        EC$(this).find('option[value!="*"][value!="**"]').remove('option');
                    } else {
                        EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(this, '*', false);
                    }
                }

                if (EC_SHOP_FRONT_NEW_OPTION_COMMON.isOptionStyleButton(this) === true) {
                    if (bIsDelete === true && bIsRequired === true) {
                        EC$(this).find('li').removeClass(EC_SHOP_FRONT_NEW_OPTION_CONS.BUTTON_OPTION_DISABLE_CLASS).addClass(EC_SHOP_FRONT_NEW_OPTION_CONS.BUTTON_OPTION_DISABLE_CLASS);
                    }

                    EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(this, '*', false);
                  //옵션 텍스트 초기화
                    EC_SHOP_FRONT_NEW_OPTION_EXTRA_DISPLAYITEM.eachCallback(this);
                }

                //첫번째 필수 옵션은 그대로 두고 두번째 필수옵션부터 remove
                if (bIsDelete !== null && bIsRequired === true) {
                    bIsDelete = true;
                }
            }
        });
    },

    /**
     * 옵션이 모두 선택되었다면 아이템코드 Set
     */
    setItemCode : function() {
        //연동형 상품 : 예외적인경우가 많아서 어쩔수가 없음...
        if (Olnk.isLinkageType(this.common.getOptionType(this.oOptionObject)) === true) {
            //선택한 값이 옵션이 아니라면 false
            if (this.validation.isItemCode(this.common.getOptionSelectedValue(this.oOptionObject)) === false) {
                return false;
            }

            //연동형 옵션
            var aSelectedValues = this.common.getAllSelectedValue(this.oOptionObject);

            //필수옵션 갯수
            var iRequiredOption = this.common.getRequiredOption(this.sOptionGroup).length;

            //선택한 옵션갯수보다 필수옵션이 많다면 false
            if (iRequiredOption > EC$(aSelectedValues).length) {
                return false;
            }
            //실제 필수옵션이 체크되어있는지
            var aOptionValues = [];
            var bIsExists = false;
            var iRequireSelectedOption = 0;

            //필수항목만 검사
            this.common.getRequiredOption(this.sOptionGroup).each(function() {
                bIsExists = false;
                aOptionValues = EC_SHOP_FRONT_NEW_OPTION_COMMON.getAllOptionValues(this);

                //필수 항목 옵션의 값을 실제 선택한옵션가눙데 존재하는지 일일히 확인해야한다
                EC$(aSelectedValues).each(function(i, iNo) {
                    //선택된 옵션중에 존재한다면 필수값이 선택된것으로 확인
                    if (EC$.inArray(iNo, aOptionValues) > -1) {
                        bIsExists = true;
                        return;
                    }
                });

                if (bIsExists === true) {
                    iRequireSelectedOption++;
                }
            });

            //전체 필수값 갯수가 선택된 필수옵션보다 많다면 false
            if (iRequiredOption > iRequireSelectedOption) {
                return false;
            }

            this.sItemCode = aSelectedValues;
        } else if (this.validation.isSeparateOption(this.oOptionObject) === true) {
            //조합분리형은 옵션값으로 파싱해서 가져와야함
            if (parseInt(this.iOptionCount) > parseInt(this.aOptionValue.length)) {
                return false;
            }

            this.sItemCode = this.data.getItemCode(this.iProductNum, this.aOptionValue.join(this.cons.OPTION_GLUE));
        } else {
            //조합분리형 이외에는 선택한 옵션의 value가 아이템코드
            this.sItemCode = this.common.getOptionSelectedValue(this.oOptionObject);
        }

    },

    /**
     * 각 옵션을 초기화및 옵션 리스트 HTML생성
     */
    setOptionHTML : function() {
        //하위옵션이 없다면(마지막 옵션을 선택한경우) 하위옵션이 없음으로 따로 만들지 않아도 된다
        if (parseInt(this.iOptionCount) === parseInt(this.aOptionValue.length)) {
            return;
        }

        if (this.oNextOptionTarget === null) {
            return;
        }

        var sSelectedOption = this.aOptionValue.join(this.cons.OPTION_GLUE);

        var aOptions = this.data.getOptionValueArray(this.iProductNum, sSelectedOption);

        //셀렉트박스일때 다음옵션 박스 초기화
        if (this.common.isOptionStyleButton(this.oNextOptionTarget) === false) {
            this.setOptionHtmlForSelect(aOptions, sSelectedOption);
        } else {
            this.setOptionHtmlForButton(aOptions, sSelectedOption);
        }
    },

    /**
     * 버튼형 옵션일 경우 해당 버튼 HTML초기화 및 해당 옵션값 Set
     * @param aOptions 옵션값 리스트
     * @param sSelectedOption 현재까지 선택된 옵션조합
     */
    setOptionHtmlForButton : function(aOptions, sSelectedOption) {
        //선택한값이 *sk ** 이면 다음옵션을 disable처리
        if (this.validation.isItemCode(this.common.getOptionSelectedValue(this.oOptionObject)) === false) {
            this.oNextOptionTarget.find('li').removeClass(this.cons.BUTTON_OPTION_DISABLE_CLASS).addClass(this.cons.BUTTON_OPTION_DISABLE_CLASS);
        } else {
            this.oNextOptionTarget.find('li').removeClass(this.cons.BUTTON_OPTION_DISABLE_CLASS);
        }

        //연동형일경우에는 disable /  select만 제거
        if (Olnk.isLinkageType(this.sOptionType) === true) {
            //하위옵션들만 selected클래스 삭제
            if (parseInt(EC$(this.oOptionObject).attr('option_sort_no')) < parseInt(EC$(this.oNextOptionTarget).attr('option_sort_no'))) {
                EC$(this.oNextOptionTarget).find('li').removeClass(this.cons.BUTTON_OPTION_SELECTED_CLASS);
                EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(this.oNextOptionTarget, '*', false);
            }
            return;
        }

        this.oNextOptionTarget.find('li').remove('li');

        var iNextOptionSortNum = this.common.getOptionSortNum(this.oNextOptionTarget);

        var bIsLastOption = false;
        //생성될 옵션이 마지막 옵션이면 옵션 Text에 추가 항목(옵션가 품절표시등)을 처리
        if (parseInt(iNextOptionSortNum) === this.iOptionCount) {
            bIsLastOption = true;
        }

        var oObject = this;
        var sOptionsHtml = '';

        //옵션 셀렉트박스 Text에 추가될 문구 처리
        var sAddText = '';
        var sItemCode = false;
        //품절옵션인데 품절옵션표시안함설정이면 삭제
        var bIsSoldout = false;
        var bIsDisplay = true;

        EC$(aOptions).each(function(i, oOption) {
            sAddText = '';
            bIsSoldout = false;
            bIsDisplay = true;
            //페이지 로딩시 저장된 해당 옵션의 HTML을 가져온다
            sOptionsHtml = oObject.data.getButonOptionHtml(oObject.iProductNum, iNextOptionSortNum, oOption.value);

            sOptionsHtml = EC$(sOptionsHtml).clone().removeClass(oObject.cons.BUTTON_OPTION_DISABLE_CLASS);
            //마지막 옵션일 경우에는
            if (bIsLastOption === true) {
                sItemCode = oObject.data.getItemCode(oObject.iProductNum, sSelectedOption + oObject.cons.OPTION_GLUE + oOption.value);

                //진열안함이면 패스
                if (oObject.common.isDisplay(oObject.iProductNum, sItemCode) === false) {
                    bIsDisplay = false;
                }

                sAddText = oObject.setAddText(oObject.iProductNum, sItemCode);

                //품절상품인경우 품절class추가
                if (oObject.common.isSoldout(oObject.iProductNum, sItemCode) === true) {
                    EC$(sOptionsHtml).removeClass(oObject.cons.BUTTON_OPTION_SOLDOUT_CLASS).addClass(oObject.cons.BUTTON_OPTION_SOLDOUT_CLASS);
                    bIsSoldout = true;
                }
            } else {
                var sOptionText = sSelectedOption + oObject.cons.OPTION_GLUE + oOption.value;
                sAddText = oObject.common.getSoldoutText(oObject.oNextOptionTarget, sOptionText);

                if (sAddText !== '') {
                    EC$(sOptionsHtml).addClass(oObject.cons.BUTTON_OPTION_SOLDOUT_CLASS);
                    bIsSoldout = true;
                }

                if (oObject.data.getDisplayFlag(oObject.iProductNum, sOptionText) === false) {
                    bIsDisplay = false;
                }
            }

            if ((oObject.bIsDisplaySolout === false && bIsSoldout === true) || bIsDisplay === false) {
                EC$(this).remove();
                return;
            }

            oObject.oNextOptionTarget.append(EC$(sOptionsHtml).attr('title', oOption.value + sAddText));
        });

        EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(this.oNextOptionTarget, '*', false);
    },

    /**
     * 셀렉트박스형 옵션일 경우 selectbox초기화 및 해당 옵션값 Set
     * @param aOptions 옵션값 리스트
     * @param sSelectedOption 현재까지 선택된 옵션조합 배열
     */
    setOptionHtmlForSelect : function(aOptions, sSelectedOption) {
        // 구분선 제외
        this.oNextOptionTarget.find('option[value!="**"]').prop('disabled', false);

        //연동형일경우에는 초기화 시키고  disable제거
        //if (Olnk.isLinkageType(this.sOptionType) === true && EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isRequireOption(this.oNextOptionTarget)) {
        if (Olnk.isLinkageType(this.sOptionType) === true) {
            var sHtml = this.data.getDefaultOptionHTML(this.common.getOptionProductNum(this.oNextOptionTarget), this.common.getOptionSortNum(this.oNextOptionTarget));
            EC$(this.oNextOptionTarget).find('option').remove();
            EC$(this.oNextOptionTarget).append(sHtml);
            EC$(this.oNextOptionTarget).find('option[value!="**"]').prop('disabled', false);
            EC$(this.oNextOptionTarget).val('*');
            return;
        }

        //옵션이 아닌 Default선택값을 제외하고 모두 삭제
        this.oNextOptionTarget.find('option[value!="*"][value!="**"]').remove();

        //선택한 옵션의 다음순서옵션항목
        var iNextOptionSortNum = this.common.getOptionSortNum(this.oNextOptionTarget);

        var bIsLastOption = false;
        //생성될 옵션이 마지막 옵션이면 옵션 Text에 추가 항목(옵션가 품절표시등)을 처리
        if (parseInt(iNextOptionSortNum) === this.iOptionCount) {
            bIsLastOption = true;
        }

        var oObject = this;
        var sOptionsHtml = '';

        var sItemCode = false;

        //옵션 셀렉트박스 Text에 추가될 문구 처리
        var sAddText = '';
        //품절옵션인데 품절옵션표시안함설정이면 삭제
        var bIsSoldout = false;
        EC$(aOptions).each(function(i, oOption) {
            sAddText = '';
            bIsSoldout = false;
            bIsDisplay = true;

            sOptionsHtml = oObject.data.getButonOptionHtml(oObject.iProductNum, iNextOptionSortNum, oOption.value);
            sOptionsHtml = EC$(sOptionsHtml).clone();

            //마지막 옵션일 경우에는 설정에따라 옵션title에 추가금액등의 text를 붙인다
            if (bIsLastOption === true) {
                sItemCode = oObject.data.getItemCode(oObject.iProductNum, sSelectedOption + oObject.cons.OPTION_GLUE + oOption.value);

                //진열안함이면 패스
                if (oObject.common.isDisplay(oObject.iProductNum, sItemCode) === false) {
                    bIsDisplay = false;
                }

                sAddText = oObject.setAddText(oObject.iProductNum, sItemCode);

                bIsSoldout = EC_SHOP_FRONT_NEW_OPTION_COMMON.isSoldout(oObject.iProductNum, sItemCode);
            } else {
                //품절문구(각 옵션마다도 보여줘야함...)
                var sOptionText = sSelectedOption + oObject.cons.OPTION_GLUE + oOption.value;
                sAddText = oObject.common.getSoldoutText(oObject.oNextOptionTarget, sOptionText);
                bIsSoldout = (sAddText === '') ? false : true;

                if (oObject.data.getDisplayFlag(oObject.iProductNum, sOptionText) === false) {
                    bIsDisplay = false;
                }
            }

            if ((oObject.bIsDisplaySolout === false && bIsSoldout === true) || bIsDisplay === false) {
                EC$(this).remove();
                return;
            }

            EC$(sOptionsHtml).val(oOption.value);
            EC$(sOptionsHtml).prop('disabled', false);
            EC$(sOptionsHtml).text(oOption.value + sAddText);

            oObject.oNextOptionTarget.append(EC$(sOptionsHtml));
        });
    },

    /**
     * 마지막 옵션에 추가될 추가항목들(추가금액, 품절 등)
     * @param iProductNum 상품번호
     * @param sItemCode 아이템 코드
     * @param oOptionElement 옵션셀렉트박스를 임의로 지정할경우
     */
    setAddText : function(iProductNum, sItemCode, oOptionElement) {
        var aText = [];

        if (typeof(oOptionElement) !== 'object') {
            oOptionElement = this.oOptionObject;
        }

        EC$(this.aExtraOptionText).each(function() {
            if (typeof(this.get) === 'function') {
                aText.push(this.get(iProductNum, sItemCode, oOptionElement));
            }
        });

        return aText.join('');
    },

    /**
     * 옵션 선택박스(셀렉트박스나 버튼)에 click 또는 change에 대한 이벤트 할당
     */
    initChooseBox : function() {
        this.cons = EC_SHOP_FRONT_NEW_OPTION_CONS;
        this.common = EC_SHOP_FRONT_NEW_OPTION_COMMON;
        this.data = EC_SHOP_FRONT_NEW_OPTION_DATA;
        this.validation = EC_SHOP_FRONT_NEW_OPTION_VALIDATION;

        var oThis = this;

        //live로 할경우에 기존 이벤트가 없어짐.
        EC$('select[option_select_element="ec-option-select-finder"]').off().change(function() {
            if (oThis.common.isOptionStyleButton(this) === true) {
                return false;
            }

            //페이지 로드가 되었는지 확인.
            if (typeof(oThis.common.isLoad) === false) {
                EC$(this).val('*');
                return false;
            }

            oThis.initialize(this, this);
        })
            .focus(function () {
                // select box change 이벤트 발생을 위해, selectedIndex 초기화
                if (this.selectedIndex > 0) {
                    this.selectedIndex = 0;
                }
            });

        try {
            EC$(document).off().on('click', 'ul[option_select_element="ec-option-select-finder"] > li', function (e) {
                var oOptionChoose = EC$(this).parent('ul');

                /*
                    ECHOSTING-194895 처리를 위해 삭제 (추가 이미지 클릭 시 해당 품목 선택 기능)
                    if (e.target.tagName === 'LI') {
                        return false;
                    }
                */

                if (EC_SHOP_FRONT_NEW_OPTION_COMMON.isOptionStyleButton(oOptionChoose) === false) {
                    return false;
                }

                //페이지 로드가 되었는지 확인.
                if (typeof(EC_SHOP_FRONT_NEW_OPTION_COMMON.isLoad) === false) {
                    return false;
                }

                //라디오버튼일경우 label태그에 상속되기때문에 click이벤트가 label input에 대해 두번 발생함
                //라디오버튼 속성이면서 발생위치가 label이면 이벤트 발생하지않고 그냥 return
                //return false이면 label클릭시 checked가 안되니깐 그냥 return
                //input 태그 자체에 이벤트를 주면 상관없지만 li태그에 이벤트를 할당하기때문에 생기는 현상같음
                if (oThis.common.isOptionStyle(oOptionChoose, oThis.cons.OPTION_STYLE_RADIO) === true && e.target.tagName.toUpperCase() === 'LABEL') {
                    return;
                }

                oThis.initialize(EC$(this).parent('ul'), this);
            });
        } catch (e) {}
    },

    /**
     * 멀팁옵션에서 옵션추가시 이벤트 재정의(버튼형은 live로 되어있으니 상관없고 select형만)
     * @param oOptionElement
     */
    initChooseBoxMulti : function()
    {
        var oThis = this;

        //live로 할경우에 기존 이벤트가 없어짐.
        EC$('.xans-product-multioption select[option_select_element="ec-option-select-finder"]').off().change(function() {
            if (oThis.common.isOptionStyleButton(this) === true) {
                return false;
            }

            //페이지 로드가 되었는지 확인.
            if (typeof(oThis.common.isLoad) === false) {
                EC$(this).val('*');
                return false;
            }

            oThis.initialize(this, this);
        });
    },

    /**
     * 옵션 선택시 필요한 attribute값등을 SET
     */
    setSelectedOptionConf : function() {
        //선택한 옵션 그룹
        this.sOptionGroup = this.common.getOptionSelectGroup(this.oOptionObject);

        //선택한 옵션값 순번
        this.iOptionIndex = parseInt(this.common.getOptionSortNum(this.oOptionObject));

        //선택한 옵션 스타일
        this.sOptionStyle = EC$(this.oOptionObject).attr(this.cons.OPTION_STYLE);

        //현재까지 선택한 옵션의 value값을 가져온다
        this.aOptionValue = this.common.getAllSelectedValue(this.oOptionObject);

        //상풉번호
        this.iProductNum = this.common.getOptionProductNum(this.oOptionObject);

        //옵션타입
        this.sOptionType = this.common.getOptionType(this.oOptionObject);

        //품절 옵션 표시여부
        this.bIsDisplaySolout = this.validation.isSoldoutOptionDisplay();

        //선택한 옵션의 다음 옵션 Element
        //선택옵션을 제거한 다음옵션
        //1 : 필수, 2 : 선택, 3 : 필수일때 1번옵션 선택후 다음옵션을 3번(연동형)
        //[option_sort_no"'+this.iOptionIndex+'"]
        oThis = this;
        this.oNextOptionTarget = null;
        EC$('[product_option_area="'+this.sOptionGroup+'"][option_product_no="'+this.iProductNum+'"]').each(function() {
            //현재선택한 옵션의 하위옵션이 아니라 상위옵션이면 패스
            if (oThis.iOptionIndex >= parseInt(oThis.common.getOptionSortNum(this))) {
                return true;//continue
            }
            //선택옵션이면 패스
            if (oThis.validation.isRequireOption(this) === false) {
                return true;
            }

            oThis.oNextOptionTarget = EC$(this);
            return false;//break
        });

        //옵션 갯수
        this.iOptionCount = EC$('[product_option_area="'+this.sOptionGroup+'"]').length;
    },

    /**
     * 버튼식 옵션일 경우 선택한 옵션을 선택처리
     */
    setSelectButton : function(oSelectedOption, bIsUnset) {
        if (this.common.isOptionStyleButton(this.oOptionObject) === true) {
            //모두 선택이 안된상태로 이벤트 실행할수있도록 selected css를 지우고 리턴
            if (bIsUnset === true) {
                EC$(oSelectedOption).removeClass(this.cons.BUTTON_OPTION_SELECTED_CLASS);
                return;
            }

            //이미 선택한 옵션값을 다시 클릭시에는 선택해제
            if (EC$(oSelectedOption).hasClass(this.cons.BUTTON_OPTION_SELECTED_CLASS) === true) {
                EC$(oSelectedOption).removeClass(this.cons.BUTTON_OPTION_SELECTED_CLASS);
                this.common.setValue(this.oOptionObject, '*', false);
                this.setRadioButtonSelect(oSelectedOption, this.oOptionObject, false);
            } else {
                //버튼형식의  옵션일 경우 선택한 옵션을 선택처리(class 명을 추가)
                //선택불가일때는 선택된상태로 보이지 않도록 하고 클리만 가능하도록 한다
                //disable상태이면 선택CSS는 적용되지 않게 처리
                var oTargetOptionElement = EC$(oSelectedOption).parent('ul');
                var sDevID = EC$(oTargetOptionElement).attr('ec-dev-id');
                var self = this;

                //조합일체형에서 구분선이 있을경우 ul태그가 따로있지만 동일옵션이므로
                //동일 ul을 구해서 모두 unselect시킨다
                EC$(oTargetOptionElement).parent().find('ul[ec-dev-id="'+sDevID+'"]').each(function() {
                    EC$(this).find('li').removeClass(self.cons.BUTTON_OPTION_SELECTED_CLASS);
                });

                EC$(oSelectedOption).addClass(this.cons.BUTTON_OPTION_SELECTED_CLASS);
                this.setRadioButtonSelect(oSelectedOption, this.oOptionObject, true);
            }
        } else {
            //셀렉트박스형 옵션일 경우 **를 선택했다면 옵션초기화
            if (this.validation.isItemCode(EC$(this.oOptionObject).val()) === false) {
                EC$(this.oOptionObject).val('*');
            }
        }
    },

    /**
     * Disable인 옵션일 경우 체크박스를 다시 해제함
     * @param oSelectedOption
     * @param oOptionObject
     * @param bIsCheck
     */
    setRadioButtonSelect : function(oSelectedOption, oOptionObject, bIsCheck)
    {
        if (EC_SHOP_FRONT_NEW_OPTION_COMMON.isOptionStyle(oOptionObject, EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_STYLE_RADIO) === false) {
            return;
        }

        EC$(oOptionObject).find('input:radio').prop('checked', false);

        //재선택시 체크해제하려면 e107c06faf31 참고
        if (bIsCheck === true) {
            EC$(oSelectedOption).find('input:radio').prop('checked', true);
        }
    }
};

var EC_SHOP_FRONT_NEW_OPTION_DATA = {

    /**
     * EC_SHOP_FRONT_NEW_OPTION_CONS 객체 Alias
     */
    cons : EC_SHOP_FRONT_NEW_OPTION_CONS,

    /**
     * EC_SHOP_FRONT_NEW_OPTION_COMMON 객체 Alias
     */
    common : EC_SHOP_FRONT_NEW_OPTION_COMMON,

    /**
     * 옵션값관 아이템코드 매칭 데이터(option_value_mapper)
     */
    aOptioValueMapper : [],

    /**
     * 각 선택된 옵션값에대한 다음옵션값 리스트를 저장
     * aOptionValueData[상품번호][빨강#$%대형] = array(key : 1, value : 옵션값, text : 옵션 Text)
     */
    aOptionValueData : {},

    /**
     * 각 상품의 품목데이터(재고 및 추가금액 정보)
     */
    aItemStockData : {},

    /**
     * 옵션의 디폴트 HTML을 저장해둠
     */
    aOptionDefaultData : {},

    /**
     * 디폴트 옵션을 저장할떄 중복을 제거하기위해서 추가
     */
    aCacheDefaultProduct : [],

    /**
     * 버튼형 옵션 Element저장시 중복제거
     */
    aCacheButtonOption : [],

    /**
     * 버튼형 옵션의 경우 각 옵션값별 컬러칩/버튼이미지/버튼이름등을 저장해둔다
     */
    aButtonOptionDefaultData : [],

    /**
     * 추가금액 노출 설정
     */
    aOptionPriceDisplayConf : [],

    /**
     * 연동형 옵션의 옵션내용을 저장
     */
    aOlnkOptionData : [],

    /**
     * 각 옵션(품목이 아닌)마다 모두 품절이면 품절표시를 위해서 추가...
     */
    aOptionSoldoutFlag : [],

    /**
     * 각 옵션(품목이 아닌)마다 모두 진열안함이면 false로 나오지 않게 하기 위해서 추가
     */
    aOptionDisplayFlag : [],

    /**
     * 페이지 로딩시 각 옵션선택박스의 옵션정보를 Parse
     */
    initData : function() {
        var oThis = this;
        EC$('select[option_select_element="ec-option-select-finder"], ul[option_select_element="ec-option-select-finder"]').each(function() {
            //해당 옵션의 상품번호
            var iProductNum = oThis.common.getOptionProductNum(this);
            //해당 옵션의 옵션순서번호
            var iOptionSortNum = oThis.common.getOptionSortNum(this);

            var sCacheKey = iProductNum + oThis.cons.OPTION_GLUE + iOptionSortNum;

            EC_SHOP_FRONT_NEW_OPTION_DATA.initializeOption(this, sCacheKey);

            //버튼형 옵션일 경우 각 Element를 캐싱
            if (EC_SHOP_FRONT_NEW_OPTION_COMMON.isOptionStyleButton(this) === true) {
                EC_SHOP_FRONT_NEW_OPTION_DATA.initializeOptionForButtonOption(this, sCacheKey);
            } else {
                EC_SHOP_FRONT_NEW_OPTION_DATA.initializeOptionForSelectOption(this, sCacheKey);
                //일반 셀렉트의 경우 기본값 (*, **)을 제외하고 삭제
                //첫번째 필수값은 option들이 disable이 아니므로 disable된 옵션들만 삭제
                var bIsProcLoading = true;

                //필수옵션만 삭제
                if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isRequireOption(this) === false) {
                    bIsProcLoading = false;
                }

                //disable만 풀어준다
                //연동형이지만 옵션추가버튼 사용시에는 지우지 않음...
                //기본으로 선택된값이 있다면 지우지 않음(구스킨 관심상품, 뉴스킨 장바구니등에서는 일단 선택한 옵션을 보여주고 선택후부터 순차로딩)
                var sValue = EC$(this).find('option[selected="selected"]').val();
                if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isItemCode(sValue) === true || (Olnk.isLinkageType(oThis.common.getOptionType(this)) === true && (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isUseOlnkButton() === true || EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isBindUseOlnkButton(iProductNum) === true))) {
                    bIsProcLoading = false;
                    EC$(this).find('option[value!="**"]').prop('disabled', false);
                }

                if (bIsProcLoading === true) {
                    EC$(this).find('option[value!="*"][value!="**"]:disabled').remove('option');
                }
            }
        });
    },

    /**
     * 각 상품의 옵션 디폴트 옵션 HTML을 저장해둔다
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     */
    initializeOption : function(oOptionChoose, sCacheKey) {
        //이미 데이터가 있다면 패스
        if (EC$.inArray(sCacheKey, this.aCacheDefaultProduct) > -1) {
            return;
        }

        this.aCacheDefaultProduct.push(sCacheKey);
        this.aOptionDefaultData[sCacheKey] = EC$(oOptionChoose).html();
    },

    initializeOptionForSelectOption : function(oOptionChoose, sCacheKey) {
        var iProductNum = EC$(oOptionChoose).attr('option_product_no');
        var oThis = this;
        //같은 상품이 여러개있을수있으므로 이미 캐싱이 안된 상품만
        if (EC$.inArray(sCacheKey, this.aCacheButtonOption) < 0) {
            var bDisabled = false;
            if (Olnk.isLinkageType(this.common.getOptionType(oOptionChoose)) === true && (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isUseOlnkButton() === true || EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isBindUseOlnkButton(iProductNum) === true)) {
                bDisabled = true;
            }

            this.aCacheButtonOption.push(sCacheKey);
            this.aButtonOptionDefaultData[sCacheKey] = [];

            EC$(oOptionChoose).find('option').each(function() {
                if (bDisabled === true && this.value !== '**') {
                    EC$(this).prop('disabled', false);
                }
                oThis.aButtonOptionDefaultData[sCacheKey][EC$(this).val()] = EC$('<div>').append(EC$(this).clone()).html();
            });
        }
    },

    /**
     * 셀렉트박스 형식이 아닌 버튼이나 이미지형 옵션일 경우 HTML자체를 옵션값 별로 저장해둔다.
     * writejs쓰기싫음여
     */
    initializeOptionForButtonOption : function(oOptionChoose, sCacheKey) {
        var oThis = this;
        var iProductNum = EC$(oOptionChoose).attr('option_product_no');
        //같은 상품이 여러개있을수있으므로 이미 캐싱이 안된 상품만
        if (EC$.inArray(sCacheKey, this.aCacheButtonOption) < 0) {
            var bDisabled = false;
            if (Olnk.isLinkageType(this.common.getOptionType(oOptionChoose)) === true && (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isUseOlnkButton() === true || EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isBindUseOlnkButton(iProductNum) === true)) {
                bDisabled = true;
            }

            this.aCacheButtonOption.push(sCacheKey);
            this.aButtonOptionDefaultData[sCacheKey] = [];

            EC$(oOptionChoose).find('li').each(function() {
                if (bDisabled === true) {
                    EC$(this).removeClass(EC_SHOP_FRONT_NEW_OPTION_CONS.BUTTON_OPTION_DISABLE_CLASS);
                }
                oThis.aButtonOptionDefaultData[sCacheKey][EC$(this).attr('option_value')] = EC$('<div>').append(EC$(this).clone()).html();
            });
        }

        var oTriggerSelect = this.getSelectClone(oOptionChoose);

        oTriggerSelect.append(EC$('<option>').val('*').text('empty'));

        var sTitle = '';
        var sValue = '';
        for (x in this.aButtonOptionDefaultData[sCacheKey]) {
            //IE8..
            if (x !== 'indexOf') {
                sTitle = EC$(oThis.aButtonOptionDefaultData[sCacheKey][x]).attr('title');
                sValue = EC$(oThis.aButtonOptionDefaultData[sCacheKey][x]).attr('option_value');

                oTriggerSelect.append(EC$('<option>').val(sValue).text(sTitle));
            }
        }

        oTriggerSelect.val('*');
        EC$(oOptionChoose).parent().append(oTriggerSelect);
    },
    /**
     * 옵션 선택 UI의 미러링 객체 생성
     * @param oOptionChoose
     * @returns {jQuery}
     */
    getSelectClone : function(oOptionChoose)
    {
        var aAttribute = {
            'product_option_area_select' : EC$(oOptionChoose).attr('product_option_area'),
            'id' : EC$(oOptionChoose).attr('ec-dev-id'),
            'name' : EC$(oOptionChoose).attr('ec-dev-name'),
            'option_title' : EC$(oOptionChoose).attr('option_title'),
            'option_type' : EC$(oOptionChoose).attr('option_type'),
            'item_listing_type' : EC$(oOptionChoose).attr('item_listing_type'),
            'composition-code' : EC$(oOptionChoose).attr('composition-code'),
            'option_code' : EC$(oOptionChoose).attr('option_code')
        };
        // 셀렉트 박스의 셀렉터가 ^=라서 클래스의 순서가 중요 
        var aClass = [];
        if (typeof(EC$(oOptionChoose).attr('ec-dev-class')) !== 'undefined') {
            aClass.push(EC$(oOptionChoose).attr('ec-dev-class'));
        }
        aClass.push('displaynone');
        var oReturn = EC$('<select required="true">').attr(aAttribute).addClass(aClass.join(' '));
        if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isRequireOption(oOptionChoose) === false) {
            // true/false를 string으로 지정해야해서 먼저 string으로 지정해주고 필요없으면 제거
            oReturn.removeAttr('required');
        }
        return oReturn;
    },

    /**
     * 버튼형 옵션의 상품 옵션값에 대한 옵션 HTML을 반환
     * @param iProductNum 상품번호
     * @param iOptionSortNum 옵션순서
     * @param sOptionValue 옵션값
     * @returns boolean 해당 옵션값에 대한 버튼 HTML
     */
    getButonOptionHtml : function(iProductNum, iOptionSortNum, sOptionValue) {
        var sCacheKey = iProductNum + this.cons.OPTION_GLUE + iOptionSortNum;

        //없을경우에는 다시 초기화
        if (typeof(this.aButtonOptionDefaultData[sCacheKey]) === 'undefined') {
            this.initData();
        }

        if (typeof(this.aButtonOptionDefaultData[sCacheKey][sOptionValue]) === 'undefined') {
            return false;
        }

        return this.aButtonOptionDefaultData[sCacheKey][sOptionValue];
    },

    /**
     * 옵션을 선택하지 않았을때 하위옵션을 초기화하기위해서 디폴트 HTML을 가져옴
     * @param iProductNum 상품번호
     * @param iOptionSortNum 옵션 순서
     */
    getDefaultOptionHTML : function(iProductNum, iOptionSortNum)
    {
        var sCacheKey = iProductNum + this.cons.OPTION_GLUE + iOptionSortNum;

        if (typeof(this.aOptionDefaultData[sCacheKey]) === 'undefined') {
            return;
        }

        return this.aOptionDefaultData[sCacheKey];
    },

    /**
     * 해당 상품의 옵션 재고 관련 데이터를 리턴
     * @param iProductNum 상품번호
     */
    getProductStockData : function(iProductNum) {
        if (typeof(this.aItemStockData[iProductNum]) === 'undefined') {
            try {
                this.aItemStockData[iProductNum] = EC_UTIL.parseJSON(eval('option_stock_data' + iProductNum));
            } catch (e) {}
        }

        if (this.aItemStockData.hasOwnProperty(iProductNum) === false) {
            return null;
        }

        return this.aItemStockData[iProductNum];
    },

    /**
     * 옵션이 모두 선택되었다면 옵션값 리턴
     * @param iProductNum 상품번호
     * @param sSelectedOptionValue 선택된 전체 옵션값
     * @returns boolean 아이템코드
     */
    getItemCode : function(iProductNum, sSelectedOptionValue) {
        if (typeof(this.aOptioValueMapper[iProductNum]) === 'undefined') {
            return false;
        }

        if (typeof(this.aOptioValueMapper[iProductNum][sSelectedOptionValue]) === 'undefined') {
            return false;
        }

        return this.aOptioValueMapper[iProductNum][sSelectedOptionValue];
    },

    /**
     * 해당 상품의 선택된 옵션의 하위 옵션을 리턴
     * @param iProductNum 상품번호
     * @param sSelectedValue 현재까지 선택된 옵션값 String(옵션1값 + EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_GLUE + 옵션2값 형식)
     * @returns 옵션리스트
     */
    getOptionValueArray : function(iProductNum, sSelectedValue) {
        if (typeof(this.aOptionValueData[iProductNum]) === 'undefined') {
            return false;
        }

        if (typeof(this.aOptionValueData[iProductNum][sSelectedValue]) === 'undefined') {
            return false;
        }

        return this.aOptionValueData[iProductNum][sSelectedValue];
    },

    /**
     * 옵션 생성에 필요한 기본데이터 정의
     */
    setDefaultData : function() {
        if (typeof(option_stock_data) !== 'undefined') {
            this.aItemStockData[iProductNo] = EC_UTIL.parseJSON(option_stock_data);
        }
        if (typeof(option_value_mapper) !== 'undefined') {
            this.aOptioValueMapper[iProductNo] = EC_UTIL.parseJSON(option_value_mapper);
        }
        if (typeof(product_option_price_display) !== 'undefined') {
            this.aOptionPriceDisplayConf[iProductNo] = product_option_price_display;
        }

        if (typeof(add_option_data) !== 'undefined') {
            var aAddOptionJson = EC_UTIL.parseJSON(add_option_data);
            for (var iAddProductNo in aAddOptionJson) {
                this.aItemStockData[iAddProductNo] = EC_UTIL.parseJSON(aAddOptionJson[iAddProductNo].option_stock_data);
                if (typeof(aAddOptionJson[iAddProductNo].option_value_mapper) !== 'undefined') {
                    this.aOptioValueMapper[iAddProductNo] = EC_UTIL.parseJSON(aAddOptionJson[iAddProductNo].option_value_mapper);
                }

                this.aOptionPriceDisplayConf[iAddProductNo] = aAddOptionJson[iAddProductNo].product_option_price_display;
            }
        }

        if (typeof(set_option_data) !== 'undefined') {
            var aSetProductData = EC_UTIL.parseJSON(set_option_data);
            for (var iSetProductNo in aSetProductData) {
                this.aItemStockData[iSetProductNo] = EC_UTIL.parseJSON(aSetProductData[iSetProductNo].option_stock_data);

                if (typeof(aSetProductData[iSetProductNo].option_value_mapper) !== 'undefined') {
                    this.aOptioValueMapper[iSetProductNo] = EC_UTIL.parseJSON(aSetProductData[iSetProductNo].option_value_mapper);
                }

                this.aOptionPriceDisplayConf[iSetProductNo] = aSetProductData[iSetProductNo].product_option_price_display;
            }
        }
    },

    /**
     * 이벤트 옵션의 다음옵션값을 세팅
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     */
    initializeOptionValue : function(oOptionChoose) {
        //상품번호
        var iProductNum = this.common.getOptionProductNum(oOptionChoose);

        //현재까지 선택된 옵션값 배열
        var aSelectedValue = this.common.getAllSelectedValue(oOptionChoose);

        var sSelectedValue = aSelectedValue.join(this.cons.OPTION_GLUE);

        //기존 선언되지 않은 옵션에대한 처리면 뱌열로 미리 선언
        //이미 옵션값이 set되어있으면 바로 리턴
        if (typeof(this.aOptionValueData[iProductNum]) === 'undefined') {
            this.aOptionValueData[iProductNum] = {};
        }
        if (typeof(this.aOptionValueData[iProductNum][sSelectedValue]) === 'undefined') {
            this.aOptionValueData[iProductNum][sSelectedValue] = new Array();
        } else {
            return;
        }

        //선택한 옵션의 순번
        var iOptionSortNum = this.common.getOptionSortNum(oOptionChoose);

        //옵션값 순서
        var iCnt = 1;
        //중복옵션값 제거하기 위해서 저장할 옵션값
        var aCheckDuplicate = [];


        //장바구니 관심상품쪽은 데이터가 이렇게되어있어서 페이지로드시에 어떻게 할수가 없네요..
        if (typeof(this.aOptioValueMapper[iProductNum]) === 'undefined') {
            this.aOptioValueMapper[iProductNum] = EC_UTIL.parseJSON(eval("option_value_mapper" + iProductNum));
        }

        for (var x in this.aOptioValueMapper[iProductNum]) {

            //옵션값을 구분자에 따라 배열로 분리(옵션값 => 아이템코드 형태
            var aOptions = x.split(EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_GLUE);

            //옵션값에서 기선택된 값과 비교하기위한 옵션값
            var sOptionValue = aOptions.splice(0, iOptionSortNum).join(this.cons.OPTION_GLUE);

            //첫번째옵션부터 마지막선택한 옵션까지의 옵션값이 똑같으면서 기존처리된 옵션값이 아니라면 배열에 저장
            if (String(sOptionValue) === String(sSelectedValue) && EC$.inArray(aOptions[0], aCheckDuplicate) < 0) {
                this.aOptionValueData[iProductNum][sSelectedValue].push({key : iCnt, value : aOptions[0]});
                iCnt++;
                aCheckDuplicate.push(aOptions[0]);
            }
        }
    },

    /**
     * 각 옵션값의 전체품절 여부
     * @param iProductNum 상품번호
     * @param sValue 옵션값
     * @returns
     */
    getSoldoutFlag : function(iProductNum, sValue) {
        if (typeof(this.aOptionSoldoutFlag[iProductNum][sValue]) === 'undefined') {
            return false;
        }

        return this.aOptionSoldoutFlag[iProductNum][sValue];
    },

    /**
     * 각 옵션값의 진열 여부
     * @param iProductNum 상품번호
     * @param sValue 옵션값
     * @returns
     */
    getDisplayFlag : function(iProductNum, sValue) {

        if (typeof(this.aOptionDisplayFlag[iProductNum][sValue]) === 'undefined') {
            return false;
        }

        return this.aOptionDisplayFlag[iProductNum][sValue];
    },

    /**
     * 각각의 옵션값(품목말고)마다 해당 옵션전체가 품절인지 체크...
     * @param oOptionChoose
     */
    initializeSoldoutFlag : function(oOptionChoose) {
        //해당 옵션의 상품번호
        var iProductNum = this.common.getOptionProductNum(oOptionChoose);

        if (typeof(this.aOptionSoldoutFlag[iProductNum]) === 'undefined') {
            this.aOptionSoldoutFlag[iProductNum] = [];
        }

        if (typeof(this.aOptionDisplayFlag[iProductNum]) === 'undefined') {
            this.aOptionDisplayFlag[iProductNum] = [];
        }

        //장바구니 관심상품쪽은 데이터가 이렇게되어있어서 페이지로드시에 어떻게 할수가 없네요..
        if (typeof(this.aOptioValueMapper[iProductNum]) === 'undefined') {
            this.aOptioValueMapper[iProductNum] = EC_UTIL.parseJSON(eval("option_value_mapper" + iProductNum));
        }

        for (var x in this.aOptioValueMapper[iProductNum]) {
            //옵션값을 구분자에 따라 배열로 분리(옵션값 => 아이템코드 형태
            var aOptions = x.split(EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_GLUE);

            var bIsSoldout = EC_SHOP_FRONT_NEW_OPTION_COMMON.isSoldout(iProductNum, this.aOptioValueMapper[iProductNum][x]);

            var bIsDisplay = EC_SHOP_FRONT_NEW_OPTION_COMMON.isDisplay(iProductNum, this.aOptioValueMapper[iProductNum][x]);

            for (var i = 1; i <= EC$(aOptions).length; i++) {
                var sOption = aOptions.slice(0, i).join(EC_SHOP_FRONT_NEW_OPTION_CONS.OPTION_GLUE);

                //일단 품절로 세팅하고 품절이 아닌게 하나라도있다면 false로 바꿔준다
                if (typeof(this.aOptionSoldoutFlag[iProductNum][sOption]) === 'undefined') {
                    this.aOptionSoldoutFlag[iProductNum][sOption] = true;
                }

                if (bIsSoldout === false) {
                    this.aOptionSoldoutFlag[iProductNum][sOption] = false;
                }

                //일단 진열안함으로 세팅후에 한개라도 진열함이있다면 true바꿔줌다
                if (typeof(this.aOptionSoldoutFlag[iProductNum][sOption]) === 'undefined') {
                    this.aOptionDisplayFlag[iProductNum][sOption] = false;
                }

                if (bIsDisplay === true) {
                    this.aOptionDisplayFlag[iProductNum][sOption] = true;
                }
            }
        }
    }
};

var EC_SHOP_FRONT_NEW_OPTION_VALIDATION = {
    /**
     * EC_SHOP_FRONT_NEW_OPTION_COMMON Obejct Alias
     */
    common : EC_SHOP_FRONT_NEW_OPTION_COMMON,

    cons : EC_SHOP_FRONT_NEW_OPTION_CONS,

    /**
     * 해당 옵션 그룹에 필수옵션이 속해있는지 여부 확인
     * @param sOptionGroup 옵션 그룹 (@see : EC_SHOP_FRONT_NEW_OPTION_GROUP_CONS)
     * @returns 필수옵션 존재 여부
     */
    checkRequiredOption : function(sOptionGroup) {
        //해당 옵션 그룹의 필수옵션 갯수
        var iRequiredOption = EC$(this.common.getRequiredOption(sOptionGroup)).length;

        return (parseInt(iRequiredOption) > 0) ? true : false;
    },

    /**
     * 해당 옵션이 필수옵션인지 확인
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     */
    isRequireOption : function(oOptionChoose) {
        return (Boolean(EC$(oOptionChoose).attr('required')) === true);
    },

    /**
     * 해당 값이 아이템코드인지 확인
     * @param sItemCode 선택한 아이템코드
     * @returns true이면 아이템코드
     * @todo 아이템코드 정규식을 추가..해야하나?? 그래야한다면 선택값여부를(*, **) 따로두고 실제 아이템코드인지 여부를 더 확인해야함
     */
    isItemCode : function(sItemCode) {
        return (EC$.inArray(sItemCode, ['*', '**']) > -1 || typeof(sItemCode) === 'undefined') ? false : true;
    },

    /**
     * 옵션값이 선택되어있는지 확인
     * @param oOptionChoose 값을 가져오려는 옵션박스 object
     */
    isOptionSelected : function(oOptionChoose) {
        return (EC$.inArray(this.common.getOptionSelectedValue(oOptionChoose), ['*', '**']) > -1) ? false : true;
    },
    
    /**
     * 옵션그룹에서 하나라도 선택이 되었는지 확인
     */
    isOptionGroupSelected : function(sOptionGroup)
    {
        var oThis = this;
        var bIsChoosen = false;
        EC$('[' + this.cons.GROUP_ATTR_NAME + '^="' + sOptionGroup + '"]').each(function() {
            if (oThis.isOptionSelected(this) === true) {
                bIsChoosen = true;
                return false;
            }
        });
        return bIsChoosen;
    },
    
    /**
     * 필수 옵션이 모두 선택된 상태인지 여부 확인
     * @param sOptionGroup 선택한 아이템코드
     * @returns boolean true이면 아이템코드
     */
    isSelectedRequiredOption : function(sOptionGroup) {
        //필수옵션이 하나도 없다면 바로 true
        if (this.checkRequiredOption(sOptionGroup) === false) {
            return true;
        }

        var oThis = this;
        var bIsComplete = true;
        EC$('[' + this.cons.GROUP_ATTR_NAME + '^="' + sOptionGroup + '"]').each(function() {

            //핑수옵션이지만 값이 선택되지 않았을경우 false
            if (oThis.isRequireOption(this) === true && oThis.isOptionSelected(this) === false) {
                bIsComplete = false;
                return false;
            }
        });

        return bIsComplete;
    },

    /**
     * 조합분리형만 아이템코드를 가져오는방식이 틀려서 확인용을 추가(연동형도 일단 조합분리형으로 인식하도록 함)
     * @param oOptionChoose 구분할 옵션박스 object
     * @returns true => 조합분리형, false => 기타옵션타입
     */
    isSeparateOption : function(oOptionChoose) {
        var sOptionTypeStr = EC$(oOptionChoose).attr('option_type');
        var sOptionListStr = EC$(oOptionChoose).attr('item_listing_type');
        return (Olnk.isLinkageType(sOptionTypeStr) === true || (sOptionTypeStr === 'T' && sOptionListStr === 'S')) ? true : false;
    },

    /**
     * 연동형 옵션 추가 버튼 사용설정을 사용하면 또 순차로딩 하지 않음
     * @returns
     */
    isUseOlnkButton : function() {
        return Olnk.getOptionPushbutton(EC$('#option_push_button'));
    },
    /**
     * 세트상품에서 연동형 옵션 추가 버튼 사용설정을 사용하면 또 순차로딩 하지 않음
     * @returns
     */
    isBindUseOlnkButton : function(iProductNum) {
        return EC$('#add_option_push_button_'+iProductNum).length > 0;
    },
    isSoldoutOptionDisplay : function() {
        return (typeof(bIsDisplaySoldoutOption) !== 'undefined') ? bIsDisplaySoldoutOption : true;
    }
};
//수량 input id
var quantity_id = '#quantity:not(.ec-debug)';
var bRestockChange = false;

EC$(function()
{
    // 기존 레거시 코드(혹 사용하는몰이 있을까 하여 유지)
    if (EC$('.ec-product-couponAjax').length > 0) {
        getPrdDetailNewAjax();
    }

    // 신규 기본디자인에 반영
    if (EC$('.ec-product-coupon').length > 0) {
        EC_SHOP_FRONT_PRODUCT_INFO_COUPON.getPrdDetailCouponAjax(iProductNo,iCategoryNo);
    }

    // ECHOSTING-90301 모바일 zoom.html 페이지에서 에러 - 예외처리
    try { TotalAddSale.setParam('product_no', iProductNo); } catch (e) {}

    EC$("select[id*='product_option_id']").each ( function () {
        EC$(this).val('*');

    });

    // 디자인 마이그레이션 - 이걸 여기서 해야할까..
    if (EC$('#NewProductQuantityDummy').length > 0 && EC$('#totalProducts').length > 0) {
        EC$('#NewProductQuantityDummy').parents('tr').remove();
    }
    // 수량 초기화
    EC$(quantity_id).val(EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity());
    EC$('input.single-quantity-input[product-no='+iProductNo+']').val(EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity());
    // 펀딩
    EC$('input.quantity').val(EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity());

    // 품절일 경우 수량 0 설정
    if (EC_FRONT_JS_CONFIG_SHOP.bDirectBuyOrderForm === true && EC_FRONT_JS_CONFIG_SHOP.bSoldout === true) {
        EC$(quantity_id).val(0);
    }

    // 판매가 초기화
    try {
        setPrice(true, false, '');
    } catch(e) {}


    // 배송타입 초기화
    if (delvtype == 'A') {
        EC$('#delv_type_A').prop('checked', true);
    }

    // 배송타입 선택
    EC$('[id^="delv_type_"]').change(function()
    {
        delvtype = EC$(this).val();

        // 해외배송이면 선결제 고정
        if (delvtype == 'B') {
            EC$('#delivery_cost_prepaid').val('P');
            if (EC$('.delv_price_C').length > 0) {
                EC$('.delv_price_B').hide();
                EC$('.delv_price_C').show();
            }
            try {
                if (document.getElementById('NaverChk_Button') != null) {
                    document.getElementById('NaverChk_Button').style.display = 'none';
                }
            } catch (e) {}
        } else {
            EC$('.delv_price_B').show();
            EC$('.delv_price_C').hide();
            try {
                if (document.getElementById('NaverChk_Button') != null) {
                    document.getElementById('NaverChk_Button').style.display = '';
                }
            } catch (e) {}
        }

    });

    // 해외 배송 전용 상품은 hidden값 처리
    var oHiddenDeliveryType = EC$('[name="delv_type"]:hidden:not(:radio)');
    if (oHiddenDeliveryType.length > 0) {
        if (EC$('input:radio[id^="delv_type_"]').is(':visible') === true) {
            delvtype = EC$('input:radio[id^="delv_type_"]:checked').val();
        } else {
            oHiddenDeliveryType.each(function() {
                // delv_type의 input태그 존재 자체가 해외배송을 사용한다는 의미
                if (EC$(this).attr('product_no') == iProductNo) {
                    delvtype = 'B';
                    return false;
                }
            });
        }
    }

    if (oSingleSelection.isItemSelectionTypeS() === true) {
        // 본체 상품만
        oSingleSelection.setProductTargetKey();

        EC$(document).on('click change', 'input.single-quantity-input, img.quantity-handle.product-no-' + iProductNo, function(e) {
            var eSelf = EC$(this);
            oSingleSelection.setProductTargetKey(eSelf);
            var iBuyUnit  = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getBuyUnitQuantity();
            var iQuantity = parseInt(oSingleSelection.getQuantityInput(eSelf).val(),10);
            if (eSelf.hasClass('up') === true) {
                iQuantity = iQuantity + iBuyUnit;
            } else if (eSelf.hasClass('down') === true) {
                iQuantity = iQuantity - iBuyUnit;
            }
            var sQuantityInputSelector = ':text,input[type=tel]';
            var sContext = 'tr[target-key="'+oSingleSelection.getProductTargetKey()+'"]';
            if (EC_MOBILE_DEVICE === true || EC_MOBILE === true) {
                sQuantityInputSelector = '[type=number]';
                if (has_option === 'F') {
                    sContext = '';
                    sQuantityInputSelector = quantity_id+'[type=tel]';
                }
            } else {
                if (has_option === 'F') {
                    sContext = '#totalProducts tbody:not(.add_products)';
                }
            }

            EC$('input'+sQuantityInputSelector, sContext).not('.ec-debug').val(iQuantity).trigger('change');
            e.stopPropagation();
        });

        EC$(document).on('click', '.xans-product-quantity a.eClearUp, .xans-product-quantity a.eClearDown', function () {
            EC$(this).find('.quantity-handle.product-no-' + iProductNo).click();
        });
    }


    try {
        var sContext = ((typeof(isOrderForm) !== 'undefined' && isOrderForm === 'T') || isNewProductSkin() === false || EC_MOBILE === true ? '' :'#totalProducts');
        if (typeof(EC_SHOP_FRONT_PRODUCT_FUNDING) === 'object' && EC_SHOP_FRONT_PRODUCT_FUNDING.isFundingProduct() === true) {
            sContext = '.xans-product-funding';
            quantity_id = '[id^="quantity_"]';
        }
        // 수량 증감 버튼(옵션 없는 상품)
        EC$(document).on({
            click: function() {
                setQuantity('click', this);
            },
            change: function() {
                setQuantity('change', this);
            }
        }, '.QuantityUp' + ',' + '.QuantityDown' + ',' + quantity_id+':not(.ec-debug)', sContext);
    } catch (e) {}

    // 옵션박스 수량 증감 버튼
    try {
        EC$(document).on({
            click: function(e) {
                if (EC$(this).hasClass('eProductQuantityClass') === true) {
                    return;
                }

                setOptionBoxQuantity('click', this);
                e.stopPropagation();
            },
            change: function(e) {
                e.preventDefault();
                if (EC$(this).hasClass('single-quantity-input') === false && EC$(this).hasClass('eProductQuantityClass') === false) {
                    return;
                }
                setOptionBoxQuantity('change', this);
            }
        }, '.eProductQuantityClass' + ',' + '.option_box_up' + ',' + '.option_box_down');

        EC$(document).on('click', 'a.eProductQuantityUpClass, a.eProductQuantityDownClass', function () {
            setOptionBoxQuantity('click', document.getElementById(EC$(this).data('target')));
        });
    } catch (e) {}


    // 옵션박스 선택상품 삭제
    try {
        EC$(document).on('click', '#totalProducts a.delete', function () {
            EC$(this).find('.option_box_del').click();
        });
        EC$(document).on('click', '.option_box_del', function (event) {
            var oSelf = EC$(this);
            // onlyone 옵션 셀렉트 박스 원복
            var eSelectedItem = EC$('#' + oSelf.attr('id').replace('_del', '_id'));
            EC$('option[value="' + eSelectedItem.val() + '"]').parent().removeAttr('is_selected');
            oSelf.parents('tr,li').last().remove();

            var sDelId = EC$(this).attr('id');
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer(true) === true) {
                parent.EC$('option[value="' + eSelectedItem.val() + '"]').parent().removeAttr('is_selected');
                parent.EC$('#' + sDelId + '').parents('tr,li').last().remove();
            }
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isExistLayer() === true) {
                if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer() === false) {
                    EC$("#productOptionIframe").contents().find('option[value="' + eSelectedItem.val() + '"]').parent().removeAttr('is_selected');
                    EC$("#productOptionIframe").contents().find('#' + sDelId + '').parents('tr,li').last().remove();
                }
            }

            var sItemCode = NEWPRD_ADD_OPTION.getPerItemCode(eSelectedItem.data('option-index'), eSelectedItem.val());
            FileOptionManager.remove('file_option_' + sItemCode);

            if (EC_FRONT_JS_CONFIG_SHOP.bDirectBuyOrderForm === true) {
                EC_SHOP_FRONT_ORDERFORM_DIRECTBUY.proc.deleteBasketProduct({
                    'item_code': eSelectedItem.val(),
                    'opt_id': eSelectedItem.attr('data-option-id')
                });
            }
            if (TotalAddSale.needRecalculatorSalePrice() === true) {
                oProductList = TotalAddSale.getProductList();
                TotalAddSale.setSubscriptionParam();
                // 옵션삭제후 재계산
                delete oProductList[eSelectedItem.val()];

                // 선택옵션없을시 ajax호출안함
                if (EC$.isEmptyObject(oProductList)) {
                    TotalAddSale.setParam('product', oProductList);
                    TotalAddSale.setTotalAddSalePrice(0);
                    setTotalData();
                } else if (EC$('input.quantity_opt').length > 0) {
                    TotalAddSale.setSoldOutFlag(false);
                    TotalAddSale.setParam('product', oProductList);
                    TotalAddSale.getCalculatorSalePrice(function () {
                        setTotalData();

                        // 적립금 / 품목금액 갱신
                        TotalAddSale.updatePrice();
                    });
                }
            } else {
                setTotalData();
            }

            try {
                if (EC$('#NaverChk_Button').length > 0) {
                    if (EC$('#NaverChk_Button').children().length < 1) {
                        event.stopPropagation(); // 이벤트 전파 중단
                        return;
                    }
                    var iSoldOut = 0;
                    EC$('.option_box_id, .soldout_option_box_id').each(function () {
                        if (checkSoldOut(EC$(this).val()) === true) {
                            iSoldOut++;
                        }
                    });
                    if (iSoldOut > 0) {
                        EC$('#NaverChk_Button').css('display', 'none');
                    } else {
                        EC$('#NaverChk_Button').css('display', 'block');
                    }
                }
            } catch (e) {}

            event.stopPropagation();
        });
    } catch (e) {}

    try {
        if (EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
            EC$(document).on('click', '.differentialShipping > a', function() {
               EC$('.differentialShipping > .layerShipping').show();
               return false;
            });

            EC$(document).on('click', '.layerShipping .btnClose', function() {
                EC$(this).parent().hide();
                 return false;
             });
        }
    } catch (e) {}

    // 차등 배송비 사용시 ToolTip 열기
    try {
        EC$(document).on('click', '.btnTooltip > a', function () {
            EC$('.btnTooltip > .differentialShipping').show();
        });
    } catch (e) {}
    // 차등 배송비 사용시 ToolTip 닫기
    EC$('.btnTooltip > .differentialShipping a').off().click(function() {
        EC$('.btnTooltip > .differentialShipping').hide();
    });

    // 차등 배송비 사용시 ToolTip 열기 (모바일)
    EC$('.differentialShipping > .btnHelp').off().click(function() {
       EC$('.differentialShipping > .layerShipping').show();
    });
    // 차등 배송비 사용시 ToolTip 닫기 (모바일)
    EC$('.differentialShipping > .layerShipping > a').off().click(function() {
        EC$('.differentialShipping > .layerShipping').hide();
    });

    try {
        // 추가입력옵션 글자 길이 체크
        EC$(document).on('keyup', '.input_addoption',function() {
            var eSelf = EC$(this);
            var iLimit = eSelf.attr('maxlength');
            addOptionWordWithObj(eSelf, eSelf.val(), iLimit);
        });
    } catch (e) {}

    EC$('ul.discountMember img.ec-front-product-show-benefit-icon').click(function() {

        EC$('ul.discountMember li > div.discount_layer').hide();

        if (EC$(this).parent().parent().has('div.discount_layer').length == 0) {
            var sBenefitType = EC$(this).attr('benefit');
            var oObj = EC$(this);
            var oHtml = EC$('<div>');
            var iBenefitProductNo = EC$(this).attr('product-no');
            oHtml.addClass('ec-base-tooltip discount_layer');

            //회원등급관리의 등급할인인 경우 class추가
            if (sBenefitType == 'MG') {
                oHtml.addClass('member_rating');
            }

            EC$(this).parent().parent().append(oHtml);
            EC$.post('/exec/front/Product/Benefitinfo', 'benefit_type='+sBenefitType+'&product_no=' + iBenefitProductNo, function(sHtml) {
                oHtml.html(sHtml);
            });

        } else {
            EC$(this).parent().parent().find('div.discount_layer').show();
        }
        return false;
    });

    try {
        EC$(document).on('click', 'div.discount_layer .close', function () {
            EC$(this).parent().hide();
            return false;
        });
    } catch (e) {}

    EC$('div.shippingFee a').click(function() {
        EC$('ul.discountMember li > div.discount_layer').hide();
        EC$('ul.discountMember li > span.arrow').hide();

        if (EC$(this).parent().parent().has('div.ec-base-tooltip').length == 0) {
            var sBenefitType = EC$(this).attr('benefit');
            var oObj = EC$(this);
            var oHtml = EC$('<div>');
            oHtml.addClass('ec-base-tooltip');
            oHtml.addClass('wrap');

            //회원등급관리의 등급할인인 경우 class추가
            if (sBenefitType == 'MG') {
                oHtml.addClass('member_rating');
            }

            EC$(this).parent().append(oHtml);
            EC$.post('/exec/front/Product/Benefitinfo', 'benefit_type=' + sBenefitType + '&product_no=' + iProductNo, function(sHtml) {
                oHtml.html(sHtml);
            });
        }

        EC$(this).parent().parent().find('div.ec-base-tooltip').show();
        EC$(this).parent().parent().find('span.arrow').show();
        return false;
    });

    try {
        EC$(document).on('click', '.ec-base-tooltip .close', function () {
            EC$(this).parent().hide();
            EC$(this).parent().parent().find('span.arrow').hide();
            EC$('.differentialShipping').hide();
            return false;
        });
    } catch (e) {}

    // 단일 상품 품절일 경우 수량 0 설정
    if (EC_FRONT_JS_CONFIG_SHOP.bSoldout === true) {
        EC$(quantity_id).val(0);
    }
    // 구매옵션레이어 사용가능 여부 세팅
    // Controller에서 확인하도록 바꿀까...
    EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.init();
    // sms 재입고 알림 레이어 팝업 노출여부 확인
    EC_SHOP_FRONT_PRODUCT_SMS_RESTOCK_LAYER.setCheckSmsRestockLayerPopup();

    // 바로구매 주문서 로그인페이지로 이동
    EC_SHOP_FRONT_NEW_PRODUCT_DIRECT_BUY.setAccessRestriction();
});

/**
 * 모바일 상품옵션Layer 닫기
 * @param bIsOptionInit 옵션선택 레이어 닫을때 선택된 옵션을 부모창과 동기화할것인지 여부
 */
function closeBuyLayer(bIsOptionInit)
{
    if (bIsOptionInit !== false) {
        var iTotalOptCnt = EC$('select[id^="' + product_option_id + '"]').length;
        EC$('select[id^="' + product_option_id + '"]').each(function (i) {
            //독립형은 이미 선택되어있는 상태이기때문에 Pass
            if (EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionType(this) === 'F') {
                return;
            }
            var sSelectOptionId = EC$(this).attr('id');
            var sParentVal = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectedValue(this);
            var oTarget = parent.EC$('#'+sSelectOptionId+'');
            parent.EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(oTarget, sParentVal);
            if (i < iTotalOptCnt - 1) {
                parent.EC$('#'+sSelectOptionId+'').trigger('change');
            }
        });

        // 파일첨부 리스트 복사
        var eFileOption = EC$('[name^="file_option"]');
        if (eFileOption.length > 0) {
            var sId = eFileOption.attr('id');
            FileOptionManager.sync(sId, parent.EC$('ul#ul_' + sId));
        }
    }
    parent.EC$('html, body').css({'overflowY':'auto', height:'auto', width:'100%'});
    if (typeof(bIsOptionInit) === 'undefined') {
        parent.EC$('#opt_layer_window').remove();
    } else {
        parent.EC$('#opt_layer_window').hide();
    }
}


/**
 * 선택한 옵션 품절여부 체크
 * @param sOptionId 옵션 id
 * @returns 품절여부
 */
function checkSoldOut(sOptionId)
{
    var aStockData = EC_UTIL.parseJSON(option_stock_data);
    var bSoldOut = false;

    // get_stock_info
    if (aStockData[sOptionId] == undefined) {
        iStockNumber = -1;
        iOptionPrice = 0;
        bStock = false;
        sIsDisplay = 'T';
        sIsSelling = 'T';
    } else {
        iStockNumber = aStockData[sOptionId].stock_number;
        iOptionPrice = aStockData[sOptionId].option_price;
        bStock = aStockData[sOptionId].use_stock;
        sIsDisplay = aStockData[sOptionId].is_display;
        sIsSelling = aStockData[sOptionId].is_selling;
    }
    if (sIsSelling == 'F' || ((iStockNumber < buy_unit || iStockNumber <= 0) && (bStock === true || sIsDisplay == 'F'))) {
        bSoldOut = true;
    }
    return bSoldOut;
}


/**
 * 옵션없는 구매수량 체크
 * @param sEventType 이벤트 타입
 * @param oObj Object정보
 */
function setQuantity(sEventType, oObj)
{
    // 단일 상품 품절일 경우 수량 계산 하지 않음.
    if (EC_FRONT_JS_CONFIG_SHOP.bSoldout === true) {
        return;
    }

    var $oQuantityElement = EC$(quantity_id);
    if (EC$('.EC-funding-checkbox').length > 0) {
        $oQuantityElement = EC$(oObj).closest('.xans-product-funding').find('input.quantity');
    }
    var iQuantity = parseInt($oQuantityElement.val(),10);
    var iBuyUnit  = parseInt(buy_unit);
    var iProductMin = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity();

    if (sEventType == 'click') {
        var iProductCustom = EC$('#product_custom').val();
        var sQuantityClass = '.' + oObj.className;
        if (sQuantityClass.indexOf('.QuantityUp') >= 0 || EC$(oObj).hasClass('QuantityUp') || EC$(oObj).hasClass('up')) {
            iQuantity = iQuantity + iBuyUnit;
        } else if (sQuantityClass.indexOf('.QuantityDown') >= 0 || EC$(oObj).hasClass('QuantityDown') || EC$(oObj).hasClass('down')) {
            iQuantity = iQuantity - iBuyUnit;
        }
    }

    if (iQuantity > product_max && product_max > 0) {
        alert(sprintf(__('최대 주문수량은 %s개 입니다.'), product_max));
        if (iBuyUnit == 1) {
            $oQuantityElement.val(product_max);
        } else {
            $oQuantityElement.val($oQuantityElement.val());
        }
        return;
    }

    // 최대 구매수량과 펀딩 제한 수량은 별개로 동작해야함
    if (EC$('.EC-funding-checkbox').length > 0) {
        var iCompositionLimit = parseInt($oQuantityElement.attr('limit-quantity'), 10);
        if (iCompositionLimit > 0 && iQuantity > iCompositionLimit) {
            alert(sprintf(__('최대 주문수량은 %s개 입니다.'), iCompositionLimit));
            $oQuantityElement.val(iCompositionLimit);
            return;
        }
    }

    if (iQuantity < iProductMin) {
        alert(sprintf(__('최소 주문수량은 %s개 입니다.'), iProductMin));
        $oQuantityElement.val(iProductMin);
        return;
    }


    $oQuantityElement.val(iQuantity);
    if (oSingleSelection.isItemSelectionTypeS() === true) {
        EC$('input.single-quantity-input[product-no='+iProductNo+']').val(iQuantity);
    }
    if (EC$('.EC-funding-checkbox').length > 0) {
        var sCompositionCode = $oQuantityElement.attr('composition-code');
        EC$('.selected-funding-item.option_box_price[composition-code="'+sCompositionCode+'"]').attr('quantity', iQuantity);
    }

    setPrice(false, false, '');

    // 총 주문금액/수량 처리
    setTotalData();

    // 구스킨인경우 판매금액 계산
    if (isNewProductSkin() === false) {
        setOldTotalPrice();
    }
}

/**
 * 옵션박스 구매수량 체크
 * @param sEventType 이벤트별 수량 체크
 * @param oObj Object정보
 */
function setOptionBoxQuantity(sEventType, oObj)
{
    var sOptionId = '', sOptionBoxId = '', sProductPrice = '';
    var iQuantity = 0;
    var iBuyUnit  = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getBuyUnitQuantity();

    if (sEventType == 'click') {
        // 구매수량 화살표로 선택
        var sType = EC$(oObj).attr('id').indexOf('_up') > 0 ? '_up' : '_down';
        sOptionBoxId = '#' + EC$(oObj).attr('id').substr(0, EC$(oObj).attr('id').indexOf(sType));
        iQuantity = parseInt(EC$(sOptionBoxId + '_quantity').val(), 10);
        sOptionId = EC$(sOptionBoxId + '_id').val();
        if (sType == '_up') {
            iQuantity = iQuantity + iBuyUnit;
        } else if (sType == '_down') {
            iQuantity = iQuantity - iBuyUnit;
        }
    } else if (sEventType == 'change') {
        // 구매수량 직접 입력
        sOptionBoxId = '#' + EC$(oObj).attr('id').substr(0, EC$(oObj).attr('id').indexOf('_quantity'));
        iQuantity = parseInt(EC$(oObj).val(), 10);
        sOptionId = EC$(sOptionBoxId + '_id').val();
    }
    // 최소 구매 수량 체크
    var iProductMin = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity();

    if (iQuantity < iProductMin) {
        alert(sprintf(__('최소 주문수량은 %s개 입니다.'), iProductMin));
        iQuantity = iProductMin;
        EC$(oObj).val(iQuantity).blur();
        return;
    }

    if (iQuantity > product_max && product_max > 0) {
        alert(sprintf(__('최대 주문수량은 %s개 입니다.'), product_max));
        iQuantity = product_max;
        EC$(oObj).val(iQuantity).blur();
        return;
    }
    var aStockData     = EC_UTIL.parseJSON(option_stock_data);
    var iOptionPrice   = 0;
    var iTotalQuantity = iQuantity;
    var iStockNumber   = 0;
    var bUseStock      = '';
    var bUseSoldOut    = '';
    var iAddOptionPrice = 0; // 연동형 옵션인 경우 판매가를 제외한 옵션 자체에 붙은 금액을 따로 보관하자

    if (Olnk.isLinkageType(sOptionType) === true) {
        var aOptionTmp = sOptionId.split('||');
        var aOptionIdTmp = new Array;
        var sOptionIdTemp = '';
        for ( i = 0; i < aOptionTmp.length; i++ ) {
            if (aOptionTmp[i] !== '' ) {
                aOptionIdTmp = aOptionTmp[i].split('_');
                if (/^\*+$/.test(aOptionIdTmp[0]) === false )  {
                    iOptionPrice = iOptionPrice + parseFloat(aStockData[aOptionIdTmp[0]].option_price);
                    iAddOptionPrice = parseFloat(aStockData[aOptionIdTmp[0]].option_price);
                    sOptionIdTemp = aOptionIdTmp[0];
                }

            }
        }
        if ( (Olnk.bAllSelectedOption === true ||  Olnk.getOptionPushbutton(EC$('#option_push_button')) === true ) && sOptionIdTemp === '') {
            sOptionIdTemp = sProductCode;
        }

        iOptionPrice = parseFloat(product_price) + iOptionPrice;

        iStockNumber   = parseInt(aStockData[sOptionIdTemp].stock_number);
        bUseStock      = aStockData[sOptionIdTemp].use_stock;
        bUseSoldOut    = aStockData[sOptionIdTemp].use_soldout;


        // iTotalQuantity 연동형 옵션의 경우 현재 옵션박스에 되어 있는 모든 품목의 재고를 더해야 한다.(추가 구성상품의 경우 따로 체크함)
        var sAddOptionBoxNum = '';
        EC$('[name="quantity_opt[]"]').each(function() {
            sAddOptionBoxNum = EC$(this).attr('id').replace('quantity','');
            if (EC$(this).attr('id').indexOf('add_') < 0 && EC$(oObj).attr('id').indexOf(sAddOptionBoxNum) < 0 ) {
                iTotalQuantity += parseInt(EC$(this).val());
            }

        });

        // 최대 재고 수량 체크
        if (bUseSoldOut === 'T' && bUseStock === true && iTotalQuantity > iStockNumber) {
            alert(sprintf(__('재고 수량이 %s개 존재합니다. 재고수량 이하로 입력해주세요.'), iStockNumber));
            EC$(oObj).val(iStockNumber);
            return;
        }
    } else {
        iStockNumber   = parseInt(aStockData[sOptionId].stock_number);
        iOptionPrice  = parseFloat(aStockData[sOptionId].option_price);
    }

    if (oSingleSelection.isItemSelectionTypeS() === true) {
        var iProductNum = iProductNo;
        var iOptionSequence = 1;
        if (option_type === 'F') {
            iOptionSequence = EC$(oObj).parents('tr.option_product').attr('target-key').split('|')[1];
        }
        EC$('input.single-quantity-input[product-no='+iProductNum+'][option-sequence='+iOptionSequence+']').val(iQuantity);

    }


    iProductPrice = getProductPrice(iQuantity, iOptionPrice, sOptionId, null, function(iProductPrice)
    {
        var bIsValidBundleObject = typeof(EC_SHOP_FRONT_PRODUCT_DEATAIL_BUNDLE) === 'object';
        var iProductNum = (has_option === 'T') ? EC$(sOptionBoxId + '_quantity').attr('product-no') : iProductNo;
        //1+N 상품일 경우 품목별 가격은 변경되지 않음
        var iTotalPrice = (bIsValidBundleObject === true && EC_SHOP_FRONT_PRODUCT_DEATAIL_BUNDLE.oBundleConfig.hasOwnProperty(iProductNum) === true) ? iOptionPrice : iOptionPrice * iQuantity;
        sProductPrice = SHOP_PRICE_FORMAT.toShopPrice(iTotalPrice);

        // ECHOSTING-58174
        if (sIsDisplayNonmemberPrice == 'T') {
            sProductPrice = sNonmemberPrice;
            iProductPrice = 0;
        }

        EC$(sOptionBoxId + '_quantity').val(iQuantity);
        EC$(sOptionBoxId + '_price').find('span').html(sProductPrice);
        EC$(sOptionBoxId + '_price').find('input').val(iProductPrice);

        // 적립금 계산
        if (typeof (mileage_val) != 'undefined') {

            var iStockPrice = 0;
            if (Olnk.isLinkageType(sOptionType) === true) {
                iStockPrice = iAddOptionPrice;
            } else if (typeof (aStockData[sOptionId].stock_price) != 'undefined' ) {
                iStockPrice = aStockData[sOptionId].stock_price;
            }
            var mileage_price = TotalAddSale.getMileageGenerateCalc(sOptionId, iQuantity);

            if (EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
                EC$(sOptionBoxId + '_mileage').html(SHOP_PRICE_FORMAT.toShopMileagePrice(mileage_price));
            } else {
                if (mileage_price > 0) {
                    EC$(sOptionBoxId + '_mileage').html(SHOP_PRICE_FORMAT.toShopMileagePrice(mileage_price));
                }
            }
            if (sIsDisplayNonmemberPrice == 'T') {
                EC$(sOptionBoxId + '_mileage').html(sNonmemberPrice);
            }
        }

        // 구매레이어
        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer(true) === true) {
            parent.EC$(sOptionBoxId + '_quantity').val(iQuantity);
            parent.EC$(sOptionBoxId + '_price').find('span').html(sProductPrice);
            parent.EC$(sOptionBoxId + '_price').find('input').val(iProductPrice);
            if (typeof (mileage_val) != 'undefined') {
                parent.EC$(sOptionBoxId + '_mileage').html(SHOP_PRICE_FORMAT.toShopMileagePrice(mileage_price));
                if (sIsDisplayNonmemberPrice == 'T') {
                    parent.EC$(sOptionBoxId + '_mileage').html(sNonmemberPrice);
                }
            }
        }
        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isExistLayer() === true) {
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer() === false) {
                EC$("#productOptionIframe").contents().find(sOptionBoxId + '_quantity').val(iQuantity);
                EC$("#productOptionIframe").contents().find(sOptionBoxId + '_price').find('span').html(sProductPrice);
                EC$("#productOptionIframe").contents().find(sOptionBoxId + '_price').find('input').val(iProductPrice);
            }
            if (typeof (mileage_val) != 'undefined') {
                EC$("#productOptionIframe").contents().find(sOptionBoxId + '_mileage').html(SHOP_PRICE_FORMAT.toShopMileagePrice(mileage_price));
                if (sIsDisplayNonmemberPrice == 'T') {
                    EC$("#productOptionIframe").contents().find(sOptionBoxId + '_mileage').html(sNonmemberPrice);
                }
            }
        }
        // 총 주문금액/수량 처리
        setTotalData();

        // 적립금 / 품목금액 갱신 (현재 품목 제외)
        TotalAddSale.updatePrice(sOptionBoxId, sOptionId);
    });
}

// 자바스크립트 number_format jsyoon
function number_format(str)
{
    str += '';

    var objRegExp = new RegExp('(-?[0-9]+)([0-9]{3})');

    while (objRegExp.test(str)) {
        str = str.replace(objRegExp,'$1,$2');
    }

    return str;
}

/**
 * 가격계산 후 판매가에 반영
 * @param bInit 초기값여부
 * @param bOption 옵션선택여부
 * @param sOptionId 단독구성형일때는 SelectBox가 여러개이므로 선택한 OptionId 필요
 */
function setPrice(bInit, bOption, sOptionId)
{
    var sQuantityString = '(' + sprintf(__('%s개'),0) + ')';

    // 판매가 대체 문구시 가격 계산 안함
    if (product_price_content == true) {
        if (sIsDisplayNonmemberPrice == 'T') {
            EC$('#totalProducts .total').html('<strong><em>'+sNonmemberPrice+'</em></strong> ' + sQuantityString + '</span>');
        }
        return false;
    }

    // 옵션이 없는 경우 수량 초기화
    if (has_option == 'F' && (isNaN(EC$(quantity_id).val()) === true || EC$(quantity_id).val() == '' || EC$(quantity_id).val().indexOf('.') > 0)) {
        EC$(quantity_id).val(product_min);
    }

    if (bInit === true) {
        setProductPriceText();
    }
    // 옵션이 없을 경우
    if (has_option == 'F') {
        setPriceHasOptionF();
    } else if (has_option == 'T'){
        if (typeof sOptionType != 'undefined' && Olnk.isLinkageType(sOptionType) === false) {
            setPriceHasOptionT(bOption, sOptionId);
        } else {
            if (Olnk.getOptionPushbutton(EC$('#option_push_button')) === false) {
                iQuantity = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity();
                if (oSingleSelection.isItemSelectionTypeS() === true) {
                    iQuantity = PRODUCTSUBMIT.getQuantity();
                    Olnk.bAllSelectedOption = true;
                }
                if (EC$('.EC-funding-checkbox').length > 0) {
                    var sCompositionCode = EC$(EC_SHOP_FRONT_NEW_OPTION_BIND.oOptionObject).attr('composition-code');
                    if (EC_SHOP_FRONT_NEW_OPTION_BIND.oOptionObject === null) {
                        sCompositionCode = EC_SHOP_FRONT_PRODUCT_FUNDING.sCurrentCompositionCode;
                    }
                    sSelector = EC$('#quantity_'+sCompositionCode);
                    iQuantity = PRODUCTSUBMIT.getQuantity(sSelector);
                    //Olnk.bAllSelectedOption = true;
                }
                Olnk.handleTotalPrice(option_stock_data, product_price, sIsDisplayNonmemberPrice,false, iQuantity);

                // 적립금 / 품목금액 갱신
                TotalAddSale.updatePrice();
            }
        }
    }

    // 적립금 처리
    setMileage(bInit);
}

/**
 *  모바일 할인가 계산 후 리턴
*/
function getMobileDcPrice( iPrice ){

    var iReturnMobileDcPrice = 0;
    var iTmpBasePrice = 0;
    var iPer = 0;

    // 정율 할인일 경우
    if (sc_mobile_dc_value_flag == 'P') {
        iPer = sc_mobile_dc_value * 0.01;
        iTmpBasePrice = iPrice * iPer;
        iTmpBasePrice = getMobileDcLimitPrice( iTmpBasePrice );
        iReturnMobileDcPrice = Math.ceil( iPrice - iTmpBasePrice );
    }
    // 금액 할인일 경우
    else{
        iReturnMobileDcPrice = iPrice - sc_mobile_dc_value;
    }

    return iReturnMobileDcPrice;
}

/**
 *  모바일 할인가 금액 절사 후 리턴
 *
*/
function getMobileDcLimitPrice( MobileDcPrice ){

    var iFloat = 0;
    var iOpp = 0;

    switch ( sc_mobile_dc_limit_value ) {

        // 절사 안함
        case "F" : return MobileDcPrice; break;

        // 원단위 절사
        case "O" :
            iFloat = 0.1;
            iOpp = 10;
        break;

        // 십원단위 절사
        case "T" :
            iFloat = 0.01;
            iOpp = 100;
        break;

        // 백원단위 절사
        case "M" :
            iFloat = 0.001;
            iOpp = 1000;
        break;
    }

    MobileDcPrice = MobileDcPrice * iFloat;

    // 반올림인지 내림인지
    if (sc_mobile_dc_limit_flag == 'L') { MobileDcPrice = Math.floor( MobileDcPrice ) * iOpp; }
    else if (sc_mobile_dc_limit_flag == 'U') { MobileDcPrice = Math.round(MobileDcPrice) * iOpp; }

    return MobileDcPrice;
}

/**
 * 적립금 계산 후 반영
 */
function setMileage(bInit)
{
    if (bInit === true && (EC_MOBILE === true || EC_MOBILE_DEVICE === true)) {
        if (sIsDisplayNonmemberPrice == 'T') {
            EC$('#span_mileage_text').html(sNonmemberPrice);
        }
    }

}

/**
 * 싸이월드 스크랩 하기
 * @param sMallId 몰아이디
 * @param iPrdNo 상품번호
 * @param iCateNo 카테번호
 * @param iSid 승인번호
 * @author 김성주 <sjkim@simplexi.com>
 */
function cyConnect(sMallId, iPrdNo, iCateNo, iSid)
{
    var strUrl = "http://api.cyworld.com/openscrap/shopping/v1/?";
    //strUrl += "xu=" + escape("http://www2.1300k.com/shop/makeGoodsXml/makeGoodsXml.php?f_goodsno="+prdNo+"&cate_no="+cate_no);
    //strUrl += "&sid=s0200002";

    strUrl += "xu=" + escape("//"+sMallId+"." + EC_ROOT_DOMAIN + "/front/php/ghost_mall/makeCyworldPrdXml.php?product_no="+iPrdNo+"&cate_no="+iCateNo+"&sid="+iSid);
    strUrl += "&sid="+iSid;

    var strOption = "width=450,height=410";

    var objWin = window.open(strUrl, 'cyopenscrap',  strOption);
    objWin.focus();
}

/**
 * 싸이월드 스크랩 설명 보여주기
 * @author 김성주 <sjkim@simplexi.com>
 */
function openNateInfo(num)
{
    if (num == "1"){
        document.getElementById('divNate').style.display="none";
    }else{
        document.getElementById('divNate').style.display="";
    }
}

/**
 * 판매가 표시설정
 */
function setProductPriceText()
{
    var sString = SHOP_PRICE_FORMAT.toShopPrice(product_price);
    if (typeof product_price_ref != 'undefined' && product_price_ref > 0) {
        // 화폐 노출 순서 설정 ECHOSTING-56540
        if (currency_disp_type == 'P') {
            sString += ' ' + txt_product_price_ref;
        } else {
            sString = txt_product_price_ref + ' ' + sString;
        }
    }
    // ECHOSTING-58174
    if (sIsDisplayNonmemberPrice == 'T') {
        sString = sNonmemberPrice;
    }

    // ECHOSTING-67418 구상품일때도 판매가 영역이 바뀌게 처리 (초기화시 최소 구매수량 개수에 맞게 노출)
    if (isNewProductSkin() === false && sIsDisplayNonmemberPrice !== 'T') {
        iPrice = getProductPrice(product_min, product_price, null, null, function(iPrice) {
            sString = SHOP_PRICE_FORMAT.toShopPrice(iPrice);
            EC$('#span_product_price_text').html(sString);
        });
    } else {
        EC$('#span_product_price_text').html(sString);
    }
    var sMobileClass = '';
    if (EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
        sMobileClass = ' class = "price"';
    }
    var sTotalPriceSelector = oSingleSelection.getTotalPriceSelector();
    var sQuantityString = '('+sprintf(__('%s개'),0)+')';
    if (oSingleSelection.isItemSelectionTypeS() === true) {
        var sStrPrice = SHOP_PRICE_FORMAT.toShopPrice(0);

        EC$(sTotalPriceSelector).html('<strong'+sMobileClass+'><em>'+sStrPrice+'</em></strong> '+sQuantityString+'</span>');
        setTotalPriceRef(0, sQuantityString);
    }

    // ECHOSTING-58174
    if (sIsDisplayNonmemberPrice == 'T') {
        if (sNonmemberPrice === "") {
            sNonmemberPrice = "-";
        }
        EC$(sTotalPriceSelector).html('<strong'+sMobileClass+'><em>'+sNonmemberPrice+'</em></strong> ' + sQuantityString + '</span>');
    }

}

/**
 * 전체 금액 리턴
 * @returns {Number}
 */
function getTotalPrice()
{
    var iTotalPrice = 0;
    EC$('.option_box_price').each(function() {
        iTotalPrice += parseInt(EC$(this).val());
    });

    return iTotalPrice;
}

/**
 * 금액설정(옵션이 없는 경우)
 */
function setPriceHasOptionF()
{
    if (EC$('#totalProducts').length === 0) {
        return;
    }
    try {
        iQuantity = parseInt(EC$(quantity_id).val().replace(/^[\s]+|[\s]+$/g,'').match(/[\d\-]+/),10);
    } catch(e) {}
    var iMaxCnt = 999999;
    if (iQuantity > iMaxCnt) {
        EC$(quantity_id).val(iMaxCnt);
        iQuantity = iMaxCnt;
    }
    // 모바일 할인가 추가.
    if (typeof (EC$('#span_product_price_mobile_text') ) != 'undefined' ) {
        try{
            var iPriceMobile = parseFloat(product_price_mobile,10);
        }
        catch(e){ var iPriceMobile = product_price; }
    }

    var iTotalPrice = getProductPrice(iQuantity, product_price, item_code, null, function(iTotalPrice){
        var sTotalOriginPrice = SHOP_PRICE_FORMAT.toShopPrice( iTotalPrice );
        var iTotalOriginPrice = iTotalPrice;

        var sItemCode = EC$('.option_box_price').attr('item_code');
        sItemCode = (typeof(sItemCode) === 'undefined') ? item_code : sItemCode;
        iVatSubTotalPrice = TotalAddSale.getVatSubTotalPrice(sItemCode);

        if (iVatSubTotalPrice != iTotalPrice && iVatSubTotalPrice != 0 && iTotalPrice != 0) {
            iTotalPrice = iVatSubTotalPrice;
        }

        var sTotalPrice = SHOP_PRICE_FORMAT.toShopPrice( iTotalPrice );
        var sTotalSalePrice = sTotalPrice;
        iTotalAddSalePrice = TotalAddSale.getTotalAddSalePrice();
        if (typeof(iTotalAddSalePrice) != 'undefined' && iTotalAddSalePrice != 0) {
            iTotalSalePrice = iTotalPrice - parseFloat(iTotalAddSalePrice, 10);
            sTotalSalePrice = SHOP_PRICE_FORMAT.toShopPrice( iTotalSalePrice );
        } else {
            iTotalSalePrice = iTotalPrice;
        }

        if (typeof(EC_SHOP_FRONT_PRODUCT_FUNDING) === 'object' && EC_SHOP_FRONT_PRODUCT_FUNDING.isFundingProduct() === true) {
            if (EC_SHOP_FRONT_NEW_OPTION_EXTRA_FUNDING.sCurrentCompositionCode === null) {
                return true;
            }
        }
        //옵션이 없는 상품이고 추가구성상품 추가시 수량처리 및 상품금액 처리
        var iAddQuantity = 0;
        if (EC$('.add_product_option_box_price').length > 0) {
            EC$('.quantity_opt').each(function() {
                iAddQuantity += parseFloat(EC$(this).val());
            });

            sTotalSalePrice = getAddProductExistTotalSalePrice(iTotalSalePrice);
        }
        var iTotalQuantity = iQuantity + iAddQuantity;

        var sQuantityString = '('+sprintf(__('%s개'), iTotalQuantity) + ')';
        // ECHOSTING-58174
        if (sIsDisplayNonmemberPrice == 'T') {
            sTotalOriginPrice = sNonmemberPrice;
            sTotalPrice = sNonmemberPrice;
            sTotalSalePrice = sNonmemberPrice;
        }

        if (EC_MOBILE === true || EC_MOBILE_DEVICE === true || (typeof(isOrderForm) !== 'undefined' && isOrderForm === 'T')) {
            EC$(oSingleSelection.getTotalPriceSelector()).html('<strong class="price">'+sTotalSalePrice+' '+sQuantityString+'</strong>');
            EC$('#quantity').html('<input type="hidden" name="option_box_price" class="option_box_price" value="'+iTotalOriginPrice+'" item_code="'+item_code+'">');
        } else {
            EC$('#totalProducts .total').html('<strong><em>' + sTotalSalePrice + '</em></strong> ' + sQuantityString + '</span>');

            //품목 할인가 보여주는 설정일 경우 할인가 노출
            var sDisplayPrice = sTotalOriginPrice;
            if (TotalAddSale.getIsUseSalePrice() === true) {
                //1+N상품은 할인가 보여주지 않음
                sDisplayPrice = (TotalAddSale.getIsBundleProduct() === true) ? '' : sTotalSalePrice;
                sDisplayPrice = '<span class="ec-front-product-item-price" code="' + item_code + '" product-no="' + iProductNo + '">' + sDisplayPrice + '</span>';
            }

            EC$('#totalProducts').find('.quantity_price').html(sDisplayPrice + '<input type="hidden" name="option_box_price" class="option_box_price" value="'+iTotalOriginPrice+'" item_code="'+item_code+'">');
            if (typeof(mileage_val) !== 'undefined' && TotalAddSale.checkVaildMileageValue(mileage_val) === true) {
                var mileage_price = TotalAddSale.getMileageGenerateCalc(item_code, iQuantity);

                if (sIsDisplayNonmemberPrice == 'T') {
                    EC$('#totalProducts').find('.mileage_price').html(sNonmemberPrice);
                } else {
                    EC$('#totalProducts').find('.mileage_price').html(SHOP_PRICE_FORMAT.toShopMileagePrice( mileage_price ));
                }
            } else {
                EC$('#totalProducts').find('.mileage').hide();
            }
        }

        if (typeof(iTotalAddSalePrice) != 'undefined' && iTotalAddSalePrice != 0) {
            setTotalPriceRef(iTotalSalePrice, sQuantityString);
        } else {
            setTotalPriceRef(iTotalPrice, sQuantityString);
        }

        // 총 주문금액/수량 처리
        setTotalData();
        // 적립금 / 품목금액 갱신
        TotalAddSale.updatePrice();
    });
}

/**
 * 금액설정(옵션이 있는 경우)
 * 복합/조합 - 단독/일체 구분없이 item_code만으로 처리하도록 변경
 */
function setPriceHasOptionT(bOption, sOptionId)
{
    if (typeof(option_stock_data) == 'undefined') {
        return;
    }

    if (sIsDisplayNonmemberPrice === 'T') {
        return;
    }

    if (bOption !== true) {
        return;
    }

    var sSelectElementId = sOptionId;
    var temp_product_option_id = product_option_id;

    //뉴상품+구스킨 : 옵션추가버튼을 이용해 추가된 옵션 select box id 예외처리
    if (sOptionId.split('_')[0] == 'add') {
        temp_product_option_id = sOptionId.split('_')[0]+'_'+sOptionId.split('_')[1]+'_'+temp_product_option_id;
    }
    if (typeof(EC$('#'+sSelectElementId).attr('composition-code')) !== 'undefined') {
        temp_product_option_id = temp_product_option_id+'_'+EC$('#'+sOptionId).attr('composition-code');
    }

    var sSoldoutDisplayText = EC_SHOP_FRONT_NEW_OPTION_EXTRA_SOLDOUT.getSoldoutDiplayText(iProductNo);
    var aStockData = EC_UTIL.parseJSON(option_stock_data);
    // bItemSelected : 모든 셀렉트 박스가 선택됐는지 여부
    var bItemSelected, bSoldOut = false;
    var sOptionId, sOptionText = '';
    var iPrice = 0;

    var iBuyUnit  = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getBuyUnitQuantity('base');
    var iProductMin = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity();

    var iQuantity = (iBuyUnit >= iProductMin ? iBuyUnit : iProductMin);
    // 조합구성 & 분리선택형
    if (option_type == 'T' && item_listing_type == 'S') {
        var aOption = new Array();
        EC$('select[id^="' + temp_product_option_id + '"]').each(function() {
            var cVal = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectedValue(this);
            if (cVal.indexOf('|') > -1) {
                cVal = cVal.split('|')[0];
            }
            aOption.push(cVal);
        });

        // 아직 totalProduct에 Element추가가 안되서 getItemCode를 사용할 수 없다.
        sOptionId = ITEM.getOldProductItemCode('[id^="'+temp_product_option_id+'"]');
        sOptionValue = aOption.join('/');
        sOptionText = aOption.join('#$%');
        if (ITEM.isOptionSelected(aOption) === true) {
            bItemSelected = true;
        }

        if (typeof(aStockData[sOptionId]) != 'undefined' && aStockData[sOptionId].stock_price != 0) {
            if (typeof(product_option_price_display) == 'undefined' || product_option_price_display === 'T') {
                sOptionText += '(' + getOptionPrice(aStockData[sOptionId].stock_price) + ')';
            }
        }

        if (bItemSelected === true && sOptionId === false) {
            alert(sprintf(__("선택하신 '%s' 옵션은 판매하지 않은 옵션입니다.\n다른 옵션을 선택해 주세요."),sOptionValue));
            throw e;
            return false;
        }
    } else {
        var sElementId = sOptionId;
        var oSelect = EC$('#'+sElementId);

        if (oSelect.attr('is_selected') !== 'T') {
            sOptionText = EC$('#' + sOptionId + ' option:selected').text();
            sOptionId = EC$('#' + sOptionId + ' option:selected').val();
            bItemSelected = true;
        } else {
            if (isNewProductSkin() === true && NEWPRD_OPTION.isOptionSelectTitleOrDivider(EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSelectedValue(oSelect)) !== true) {
                alert(__('이미 선택되어 있는 옵션입니다.'));
                NEWPRD_OPTION.resetSelectElement(oSelect);
                return false;
            }
            sOptionId = '*';
        }

        // 독립선택형 옵션별로 한개씩 선택시
        if (oSingleSelection.isItemSelectionTypeM() === true && typeof(is_onlyone) === 'string' && is_onlyone === 'T' && isNewProductSkin() === true) {

            if (NEWPRD_OPTION.isOptionSelectTitleOrDivider(oSelect.val()) !== true) {
                EC$('#'+sElementId).attr('is_selected','T');
            }
        }

        if (ITEM.isOptionSelected(sOptionId) === false) {
            bItemSelected = false;
        }
    }


    if (checkOptionBox(sOptionId) === true) {
        alert(__('이미 선택되어 있는 옵션입니다.'));
        NEWPRD_OPTION.resetSelectElement(oSelect);
        return false;
    }

    // get_stock_info
    if (aStockData[sOptionId] == undefined) {
        iStockNumber = -1;
        iOptionPrice = 0;
        bStock = false;
        sIsDisplay = 'T';
        sIsSelling = 'T';
        sIsReserveStat = 'N';
    } else {
        iStockNumber = aStockData[sOptionId].stock_number;
        iOptionPrice = aStockData[sOptionId].option_price;
        bStock = aStockData[sOptionId].use_stock;
        sIsDisplay = aStockData[sOptionId].is_display;
        sIsSelling = aStockData[sOptionId].is_selling;
        sIsReserveStat = aStockData[sOptionId].is_reserve_stat; //이건 어디서
    }

    if (EC_SHOP_FRONT_NEW_OPTION_VALIDATION.isItemCode(sOptionId) === true && typeof(EC_SHOP_FRONT_PRODUCT_DEATAIL_BUNDLE.oBundleConfig[iProductNo]) === 'object') {
        iOptionPrice = aStockData[sOptionId].option_price - aStockData[sOptionId].stock_price;
    }
    if (sIsSelling == 'F' || ((iStockNumber < iBuyUnit || iStockNumber <= 0) && (bStock === true || sIsDisplay == 'F'))) {
        //뉴상품+구스디 스킨 (옵션추가 버튼나오는 디자인 - 옵션선택시 재고체크)
        if (EC$('#totalProducts').length <= 0) {
            var aOptionName = new Array();
            var aOptionText = new Array();

            aOptionName = option_name_mapper.split('#$%');
            aOptionText = sOptionText.split('#$%');
            for ( var i = 0; i < aOptionName.length; i++) {
                aOptionText[i] = aOptionName[i]+':'+aOptionText[i];
            }
            option_text = aOptionText.join('\n');
            alert(__('이 상품은 현재 재고가 부족하여 판매가 잠시 중단되고 있습니다.') + '\n\n' + __('제품명') + ' : ' + product_name + '\n\n' + __('재고없는 제품옵션') + ' : \n' + option_text);
            EC_SHOP_FRONT_NEW_OPTION_COMMON.setValue(EC$('#' + sSelectElementId), '*');
        }
        bSoldOut = true;
        sOptionText = sOptionText.split('#$%').join('/').replace('['+sSoldoutDisplayText+']', '') + ' <span class="soldOut">['+sSoldoutDisplayText+']</span>';
    } else {
        sOptionText = sOptionText.split('#$%').join('/');
    }

    if (typeof(EC$('#'+sSelectElementId).attr('composition-code')) !== 'undefined') {
        iQuantity = PRODUCTSUBMIT.getQuantity(EC$('.xans-product-funding').find('#quantity_'+EC$('#'+sSelectElementId).attr('composition-code')));
    }
    //예약주문|당일발송
    if (aStockData[sOptionId] !== undefined) {
        if (aReserveStockMessage['show_stock_message'] === 'T' && sIsReserveStat !== 'N') {
            var sReserveStockMessage = '';
            bSoldOut = false; //품절 사용 안함

            sReserveStockMessage = aReserveStockMessage[sIsReserveStat];
            sReserveStockMessage = sReserveStockMessage.replace(aReserveStockMessage['stock_message_replace_name'], iStockNumber);
            sReserveStockMessage = sReserveStockMessage.replace('[:PRODUCT_STOCK:]', iStockNumber);

            sOptionText = sOptionText.replace(sReserveStockMessage, '') + ' <span class="soldOut">'+sReserveStockMessage+'</span>';
        }
    }

    if (oSingleSelection.isItemSelectionTypeS() === true) {
        iQuantity = PRODUCTSUBMIT.getQuantity();
        if (option_type === 'F') {
            var iOptionSequence = EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSortNum(oSelect);
            iQuantity = PRODUCTSUBMIT.getQuantity(EC$('[product-no='+iProductNo+'][option-sequence='+iOptionSequence+']'));
        }
    }

    iPrice = getProductPrice(iQuantity, iOptionPrice, sOptionId, bSoldOut, function(iPrice){
        // 옵션박스 호출
        if (bItemSelected === true) {
            // 구상품스킨일때는 옵션박스 호출안함
            if (isNewProductSkin() === false) {
                if (sIsDisplayNonmemberPrice == 'T') {
                    EC$('#span_product_price_text').html(sNonmemberPrice);
                } else {
                    EC$('#span_product_price_text').html(SHOP_PRICE_FORMAT.toShopPrice(iPrice));
                }
            } else {
                setOptionBox(sOptionId, sOptionText, iPrice, bSoldOut, sSelectElementId, sIsReserveStat, iQuantity);
            }

            if (typeof (EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET) !== 'undefined') {
                EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET.bIsLoadedPriceAjax = true;
            }
        }
    });
}

/**
 * 옵션 사용가능 체크
 */
function checkOptionBox(sOptionId)
{
    if (oSingleSelection.isItemSelectionTypeS() === true) {
        return false;
    }
    if (typeof(EC_SHOP_FRONT_PRODUCT_FUNDING) === 'object' && EC_SHOP_FRONT_PRODUCT_FUNDING.isFundingProduct() === true) {
        return false;
    }
    var bSelected = false;

    // 이미 선택된 옵션은 아무 처리도 하지 않도록 처리한다.
    EC$('.option_box_id').each(function(i) {
        if (EC$(this).val() == sOptionId) {
            bSelected = true;
        }
    });

    EC$('.soldout_option_box_id').each(function(i) {
        if (EC$(this).val() == sOptionId) {
            bSelected = true;
        }
    });

    return bSelected;
}

/*
 * 옵션선택 박스 설정
 * @todo totalproduct id를 컨트롤러로 밀어야함
 */
function setOptionBox(sItemCode, sOptionText, iPrice, bSoldOut, sSelectElementId, sIsReserveStat, iManualQuantity)
{
    var sReadonly = '';
    var oSelect = EC$("#"+sSelectElementId);

    // 필수 추가옵션 작성여부 검증
    if (checkAddOption() !== true) {
        delete oProductList[sItemCode];
        NEWPRD_ADD_OPTION.resetSelectElement(oSelect);

        // 독립선택형 옵션별로 한개씩 선택시
        if (typeof(is_onlyone) === 'string' && is_onlyone === 'T' && isNewProductSkin() === true) {
            oSelect.removeAttr('is_selected');
        }

        return false;
    }

    if (checkOptionBox(sItemCode) === true) {
        alert(__('이미 선택되어 있는 옵션입니다.'));
        NEWPRD_OPTION.resetSelectElement(oSelect);
        return false;
    }

    var iBuyUnit  = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getBuyUnitQuantity('base');
    var iProductMin = EC_FRONT_NEW_PRODUCT_QUANTITY_VALID.getProductMinQuantity();

    if (parseInt(buy_unit,10) > 1) {
        sReadonly = 'readonly';
    }

    var sStrPrice = SHOP_PRICE_FORMAT.toShopPrice(iPrice);

    var iQuantity = (iBuyUnit >= iProductMin ? iBuyUnit : iProductMin);
    if (typeof(iManualQuantity) !== 'undefined') {
        iQuantity = iManualQuantity;
    }


    // 적립금 추가 필요
    var iMileageVal = 0;
    var sMileageIcon = (typeof(mileage_icon) != 'undefined') ? mileage_icon : '//img.echosting.cafe24.com/design/common/icon_sett04.gif';
    var sMileageAlt  = (typeof(mileage_icon_alt) != 'undefined') ? mileage_icon_alt : '';

    if (typeof(option_stock_data) !== 'undefined') {
        var aStockData = EC_UTIL.parseJSON(option_stock_data);
    }

    if (typeof (mileage_val) != 'undefined') {
        var iStockPrice = 0;
        if (Olnk.isLinkageType(option_type) === true) {
            var aOptionTmp = sItemCode.split('||');
            var aOptionIdTmp = new Array;
            var sItemCodeTemp = '';
            for ( i = 0; i < aOptionTmp.length; i++ ) {
                if (aOptionTmp[i] !== '' ) {
                    aOptionIdTmp = aOptionTmp[i].split('_');
                    if (/^\*+$/.test(aOptionIdTmp[0]) === false )  {
                        iStockPrice = parseFloat(aStockData[aOptionIdTmp[0]].option_price);
                    }
                }
            }
        } else if (typeof (aStockData[sItemCode].stock_price) != 'undefined' ) {
            iStockPrice = aStockData[sItemCode].stock_price;
        }
        iMileageVal = TotalAddSale.getMileageGenerateCalc(sItemCode, iQuantity);
    }
    var sMileageVal = SHOP_PRICE_FORMAT.toShopMileagePrice(iMileageVal);
    // ECHOSTING-58174
    if (sIsDisplayNonmemberPrice == 'T') {
        sStrPrice = sNonmemberPrice;
        sMileageVal = sNonmemberPrice;
    }


    var sProductName = product_name;
    if (sProductName != null) {
        sProductName = product_name.replace(/\\"/g, '"');
    }

    var aAddOption = NEWPRD_ADD_OPTION.getCurrentAddOption();

    var sAddOptionTitle = '';
    var iIndex = 1;
    if (parseInt(EC$('#totalProducts > table > tbody').find('tr.option_product').length) > 0) {
        // max
        iIndex = parseInt(EC$('#totalProducts > table > tbody').find('tr.option_product').last().data('option-index')) + 1;
    }
    var iTargetKey = iProductNo;
    if (option_type === 'F') {
        iTargetKey = iProductNo+'|'+ EC_SHOP_FRONT_NEW_OPTION_COMMON.getOptionSortNum(oSelect);
    }

    var sDisplayOption = '';
    /**
     * 옵션선택시 바로 장바구니 담기 상태라면 hide처리
     * @see EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET.setUseDirectBasket()
     */
    if (typeof(EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET) !== 'undefined' && EC_SHOP_FRONT_NEW_OPTION_EXTRA_DIRECT_BASKET.isAvailableDirectBasket(oSelect) === true) {
        sDisplayOption = 'displaynone';
    }

    var sOptionBoxId = 'option_box' + iIndex;
    var sOptionId = (typeof(aStockData[sItemCode]) != 'undefined' && typeof(aStockData[sItemCode].option_id) != 'undefined') ? aStockData[sItemCode].option_id : '';
    var sTableRow = '<tr class="option_product ' + sDisplayOption + '" data-option-index="'+iIndex+'" target-key="'+iTargetKey+'">';

    // 품목 구분을 위한 코드
    var _sItemCode = NEWPRD_ADD_OPTION.getPerItemCode(iIndex, sItemCode);
    var sHtml = NEWPRD_ADD_OPTION.getPerAddOptionTemplate(_sItemCode);
    if (NEWPRD_ADD_OPTION.isUsePerAddOption() === false) { // 개별 입력 옵션이 아닐때
        sAddOptionTitle = NEWPRD_ADD_OPTION.getCurrentAddOptionTitle(aAddOption);
    }
    if (EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
        sOptionText = '<p class="product"><strong>' + sProductName + '</strong><br /> - <span>' + sAddOptionTitle + sOptionText + '</span></p>';

        if (bSoldOut === true) {
            try {
                if (EC$('#NaverChk_Button').length > 0 && EC$('#NaverChk_Button').children().length > 0) {
                    EC$('#NaverChk_Button').css('display', 'none');
                }
            } catch(e) {}

            sTableRow += '<td>';
            sTableRow += '<input type="hidden" class="soldout_option_box_id" id="'+sOptionBoxId+'_id" value="'+sItemCode+'">'+sOptionText;
            sTableRow += '<p><input type="number" readonly value="0"/> ';
            sTableRow += '<a href="#none" class="up"><img width="30" height="27" src="//img.echosting.cafe24.com/mobileWeb/common/btn_quantity_up.png"/></a> &nbsp;';
            sTableRow += '<a href="#none" class="down"><img width="30" height="27" src="//img.echosting.cafe24.com/mobileWeb/common/btn_quantity_down.png"/></a></span></p></td>';
            sTableRow += '<td class="right"><strong class="price">'+sStrPrice+'</strong></td>';
            sTableRow += '<td class="center"><a href="#none"><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_price_delete.gif" alt="삭제" id="'+sOptionBoxId+'_del" class="option_box_del" /></a></td>';
        } else {

            //ECHOSTING 162635 예약주문 속성추가
            var sInputHiddenReserved = 'data-item-reserved="' + sIsReserveStat + '" ';
            if (sHtml) {
                sTableRow += '<td colspan="3">' +
                    '<table summary="" border="1">' +
                    '<caption>상품 목록</caption>' +
                    '<colgroup>' +
                    '<col style="width:auto;">' +
                    '<col style="width:100px;">' +
                    '<col style="width:35px;">' +
                    '</colgroup>' +
                    '<thead>' +
                    '<tr>' +
                    '<th scope="col">' + __('상품 정보') + '</th>' +
                    '<th scope="col">' + __('가격') + '</th>' +
                    '<th scope="col">' + __('삭제') + '</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody><tr>';
            }
            sTableRow += '<td>';
            sTableRow += '<input type="hidden" class="option_box_id" id="' + sOptionBoxId + '_id" value="' + sItemCode + '" name="item_code[]" data-item-add-option="' + escape(aAddOption.join(NEWPRD_OPTION.DELIMITER_SEMICOLON)) + '"' + sInputHiddenReserved + ' data-option-id="' + sOptionId + '"  data-option-index="' + iIndex + '">' + sOptionText;
            sTableRow += '<p><input type="number" id="'+sOptionBoxId+'_quantity" name="quantity_opt[]" autocomplete="off" class="quantity_opt eProductQuantityClass" '+sReadonly+' value="'+iQuantity+'" product-no="'+iProductNo+'"/> ';
            sTableRow += '<a href="#none" class="up eProductQuantityUpClass" data-target="'+sOptionBoxId+'_up"><img width="30" height="27" src="//img.echosting.cafe24.com/mobileWeb/common/btn_quantity_up.png" id="'+sOptionBoxId+'_up" class="option_box_up" alt="up" /></a> &nbsp;';
            sTableRow += '<a href="#none" class="down eProductQuantityDownClass" data-target="'+sOptionBoxId+'_down"><img width="30" height="27" src="//img.echosting.cafe24.com/mobileWeb/common/btn_quantity_down.png" id="'+sOptionBoxId+'_down" class="option_box_down" alt="down" /></a></p></td>';
            sTableRow += '<td class="right"><strong id="'+sOptionBoxId+'_price" class="price"><input type="hidden" class="option_box_price" value="'+iPrice+'" product-no="'+iProductNo+'" item_code="'+sItemCode+'"><span class="ec-front-product-item-price" code="' + sItemCode + '" product-no="'+iProductNo+'">'+sStrPrice+'</span></strong>';
            if (TotalAddSale.checkVaildMileageValue(iMileageVal) === true && sIsMileageDisplay === 'T') {
                sTableRow += '<span class="mileage">(<img src="'+sMileageIcon+'" alt="'+sMileageAlt+'" /> <span id="'+sOptionBoxId+'_mileage" class="mileage_price" code="' + sItemCode + '">'+sMileageVal+'</span>)</span>';
            }
            sTableRow += '</td>';
            sTableRow += '<td class="center"><a href="#none" class="delete"><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_price_delete.gif" alt="삭제" id="'+sOptionBoxId+'_del" class="option_box_del" /></a></td>';
            if (sHtml) {
                sTableRow += '</tr>';
                sTableRow +=  sHtml;
                sTableRow += '</tbody></table></td>';
                sTableRow += '</tbody></table></td>';
            }
        }
        sTableRow += '</tr>';

        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer(true) === true) {
            parent.EC$('#totalProducts > table > tbody').last().append(sTableRow);
        }
        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isExistLayer() === true) {
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer() === false) {
                EC$("#productOptionIframe").contents().find('#totalProducts > table > tbody').last().append(sTableRow);
            }
        }
    } else {
        sOptionText = '<p class="product">' + sProductName + '<br /> - <span>' + sAddOptionTitle + sOptionText + '</span></p>';

        if (bSoldOut === true) {
            try {
                if (EC$('#NaverChk_Button').length > 0 && EC$('#NaverChk_Button').children().length > 0) {
                    EC$('#NaverChk_Button').css('display', 'none');
                }
            } catch(e) {}
            sTableRow += '<td><input type="hidden" class="soldout_option_box_id" id="'+sOptionBoxId+'_id" value="'+sItemCode+'">'+sOptionText+'</td>';
            sTableRow += '<td><span class="quantity" style="width:65px;"><input type="text" '+sReadonly+' value="0"/><a href="#none" class="up"><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_count_up.gif" alt="수량증가" /></a><a href="#none" class="down"><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_count_down.gif" alt="수량감소" /></a></span>';
            sTableRow += '<a href="#none" class="delete"><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_price_delete.gif" alt="삭제" id="'+sOptionBoxId+'_del" class="option_box_del" /></a></td>';
            sTableRow += '<td class="right"><span id="'+sOptionBoxId+'_price"><span>'+sStrPrice+'</span></span>';
        } else {

            //ECHOSTING 162635 예약주문 속성추가
            var sInputHiddenReserved = 'data-item-reserved="' + sIsReserveStat + '" ';
            if (sHtml) {
                sTableRow += '<td colspan="3">' +
                    '<table>' +
                    '<colgroup>' +
                    '<col style="width:284px;">' +
                    '<col style="width:80px;">' +
                    '<col style="width:110px;">' +
                    '</colgroup>' +
                    '<thead>' +
                    '<tr>' +
                    '<th scope="col">' + __('상품명') + '</th>' +
                    '<th scope="col">' + __('상품수') + '</th>' +
                    '<th scope="col">' + __('가격') + '</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody><tr>';
            }
            sTableRow += '<td><input type="hidden" class="option_box_id" id="' + sOptionBoxId + '_id" value="' + sItemCode + '" name="item_code[]" data-item-add-option="' + escape(aAddOption.join(NEWPRD_OPTION.DELIMITER_SEMICOLON)) + '"' + sInputHiddenReserved + ' data-option-id="' + sOptionId + '"  data-option-index="' + iIndex + '">' + sOptionText + '</td>';
            sTableRow += '<td><span class="quantity" style="width:65px;">';
            sTableRow += '<input type="text" id="'+sOptionBoxId+'_quantity" name="quantity_opt[]" class="quantity_opt eProductQuantityClass" '+sReadonly+' value="'+iQuantity+'" product-no="'+iProductNo+'"/>';
            sTableRow += '<a href="#none" class="up eProductQuantityUpClass"" data-target="'+sOptionBoxId+'_up" ><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_count_up.gif" id="'+sOptionBoxId+'_up" class="option_box_up" alt="수량증가" /></a>';
            sTableRow += '<a href="#none" class="down eProductQuantityDownClass" data-target="'+sOptionBoxId+'_down"><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_count_down.gif" id="'+sOptionBoxId+'_down" class="option_box_down" alt="수량감소" /></a>';
            sTableRow += '</span>';
            sTableRow += '<a href="#none" class="delete"><img src="//img.echosting.cafe24.com/design/skin/default/product/btn_price_delete.gif" alt="삭제" id="'+sOptionBoxId+'_del" class="option_box_del" /></a></td>';
            sTableRow += '<td class="right"><span id="'+sOptionBoxId+'_price">';
            sTableRow += '<input type="hidden" class="option_box_price" value="'+iPrice+'" product-no="'+iProductNo+'" item_code="'+sItemCode+'">';
            sTableRow += '<span class="ec-front-product-item-price" code="' + sItemCode + '" product-no="'+iProductNo+'">'+sStrPrice+'</span></span>';
        }

        if (TotalAddSale.checkVaildMileageValue(iMileageVal) === true && sIsMileageDisplay === 'T') {
            sTableRow += '<span class="mileage">(<img src="'+sMileageIcon+'" alt="'+sMileageAlt+'" /> <span id="'+sOptionBoxId+'_mileage" class="mileage_price" code="' + sItemCode + '">'+sMileageVal+'</span>)</span>';
        }

        if (sHtml) {
            sTableRow += '</tr>';
            sTableRow += sHtml;
            sTableRow += '</tbody></table></td>';
        }
        sTableRow += '</td></tr>';
    }


    if (0 == EC$('#totalProducts > table > tbody.option_products').length) {
        EC$('#totalProducts > table > tbody').last().addClass("option_products").after(EC$('<tbody class="add_products"/>'));
    }

    if (EC$('.EC-funding-checkbox').length === 0) {
        EC$('#totalProducts > table > tbody.option_products').append(sTableRow);
        if (typeof add_option_file_input !== 'undefined') {
            // FileOptionManager에서 관리 될 수 있도록 등록
            var sId = 'file_option_' + _sItemCode;
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer(true) === true) {
                parent.FileOptionManager.put(sId, add_option_file_input.option);
            }
            FileOptionManager.put(sId, add_option_file_input.option);
        }
    } else {
        if (has_option === 'T') {
            var sCompositionCode = EC_SHOP_FRONT_NEW_OPTION_EXTRA_FUNDING.sCurrentCompositionCode;
            EC_SHOP_FRONT_PRODUCT_FUNDING.appendSelectedItem(sItemCode, sCompositionCode);
        }
    }
    // 총 주문금액/수량 처리
    setTotalData();

    //적립금 / 품목금액 업데이트
    TotalAddSale.updatePrice(sOptionBoxId, sItemCode);
}

/**
 * 총 상품금액/수량 적용
 */
function setTotalData()
{
    // 실제 계산
    var iTotalCount = 0;
    var iTotalPrice = 0;
    var iVatSubTotalPrice = 0;
    var aEventQuantity = new Array();
    var aEventQuantityCheck = {};
    //add_product_option_box_price추가구성상품
    var bIsValidBundleObject = typeof(EC_SHOP_FRONT_PRODUCT_DEATAIL_BUNDLE) === 'object';
    var fEventProductPrice = 0;

    EC$('.option_box_price, .option_add_box_price, .add_product_option_box_price').each(function(i) {
        var iProductNum = (has_option === 'T') ? EC$(this).attr('product-no') : iProductNo;
        var sItemCode = EC$(this).attr('item_code');
        if (parseInt(iProductNum) === parseInt(iProductNo) && bIsValidBundleObject === true && EC_SHOP_FRONT_PRODUCT_DEATAIL_BUNDLE.oBundleConfig.hasOwnProperty(iProductNum) === true) {
            if (has_option === 'T') {
                var iSingleQuantity = parseInt(EC$('.quantity_opt[product-no="'+iProductNum+'"]').eq(i).val(),10);
            } else {
                var iSingleQuantity = parseInt(EC$('input[name="quantity_opt[]"]').eq(i).val(),10);
            }

            if (typeof(aEventQuantityCheck[iProductNum]) === 'undefined') {
                aEventQuantityCheck[iProductNum] = 0;
                aEventQuantity.push({'product_no' : iProductNum});
            }

            aEventQuantityCheck[iProductNum] += iSingleQuantity;
        } else if (typeof(EC$(this).attr('composition-code')) !== 'undefined') {
            var sCompositionCode = EC$(this).attr('composition-code');
            var iQuantity = EC$('#quantity_'+sCompositionCode).val();
            iTotalPrice = iTotalPrice + (EC$(this).val() * iQuantity);
        } else {
            if (typeof EC_FRONT_JS_CONFIG_SHOP.bSoldout === 'undefined') {
                iTotalPrice += parseFloat(EC$(this).val());
                iVatSubTotalPrice += TotalAddSale.getVatSubTotalPrice(sItemCode);
            }
        }
    });
    EC$(aEventQuantity).each(function() {
        fEventProductPrice = fEventProductPrice + (product_price * EC_SHOP_FRONT_PRODUCT_DEATAIL_BUNDLE.getQuantity(aEventQuantityCheck[this.product_no], this.product_no));
    });
    iTotalPrice = iTotalPrice + fEventProductPrice;

    if (iVatSubTotalPrice != iTotalPrice && iVatSubTotalPrice != 0 && iTotalPrice != 0) {
        iTotalPrice = iVatSubTotalPrice;
    }
    iTotalAddSalePrice = TotalAddSale.getTotalAddSalePrice();
    if (typeof(iTotalAddSalePrice) != 'undefined' && iTotalAddSalePrice != 0) {
        iTotalPrice -= parseFloat(iTotalAddSalePrice, 10);
    }

    iTotalPrice = (iTotalPrice <= 0) ? 0 : iTotalPrice;
    var sStrPrice = SHOP_PRICE_FORMAT.toShopPrice(iTotalPrice);

    iTotalCount = EC_SHOP_FRONT_PRODUCT_INFO.getTotalQuantity();
    var sQuantityString = '('+sprintf(__('%s개'),iTotalCount)+')';

    // ECHOSTING-58174
    if (sIsDisplayNonmemberPrice == 'T') {
        sStrPrice = sNonmemberPrice;
    }
    var sTotalPriceSelector = oSingleSelection.getTotalPriceSelector();
    // 실제 노출
    if (EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
        EC$(sTotalPriceSelector).html('<strong class="price">'+sStrPrice+'</strong> '+sQuantityString);

        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer(true) === true) {
            parent.EC$(sTotalPriceSelector).html('<strong class="price">'+sStrPrice+'</strong> '+sQuantityString);
        }
        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isExistLayer() === true) {
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer() === false) {
                EC$("#productOptionIframe").contents().find(sTotalPriceSelector).html('<strong class="price">'+sStrPrice+'</strong> '+sQuantityString);
            }
        }
    } else {
        EC$(sTotalPriceSelector).html('<strong><em>'+sStrPrice+'</em></strong> '+sQuantityString+'</span>');
    }

    setTotalPriceRef(iTotalPrice, sQuantityString);
    setProductPriceTaxTypeText(iTotalPrice);
    setActionButtonVisible();
}




/**
 * 예약주문, 바로구매 버튼 , 정기 배송 버튼
 */
var setActionButtonVisible = function ()
{
    var sActionButtonSelector = '#btnBuy, #actionBuy, #actionBuyClone, #actionBuyCloneFixed';
    var sReserveSelector = '#btnReserve, #actionReserve, #actionReserveClone, #actionReserveCloneFixed';
    var sActionButtonRegular = '#btnRegularDeliveryCloneFixed, #btnRegularDelivery';

    var oOptionBox = EC$('.option_box_id');
    var oSoldoutOptionBox = EC$('.soldout_option_box_id');
    var bIsReserveStatus = oOptionBox.length === oOptionBox.filter('[data-item-reserved="R"]').length;

    if (oOptionBox.length > 0) {
        EC$(sActionButtonSelector).show();
        EC$(sReserveSelector).hide();
    }

    if (oSoldoutOptionBox.length > 0 || oOptionBox.length < 1) {
        EC$(sActionButtonSelector).show();
        EC$(sReserveSelector).hide();
        setActionButtonRegular(sActionButtonSelector, sReserveSelector, sActionButtonRegular);
        return;
    }

    if (bIsReserveStatus) {
        EC$(sActionButtonSelector).hide();
        EC$(sReserveSelector).removeClass("displaynone").show();
        EC$(sActionButtonRegular).hide();
        return;
    }

    setActionButtonRegular(sActionButtonSelector, sReserveSelector, sActionButtonRegular);
};

/**
 * 정기 배송 버튼
 */
var setActionButtonRegular = function (sActionButtonSelector , sReserveSelector, sActionButtonRegular)
{
    if (EC_FRONT_JS_CONFIG_SHOP.bRegularConfig === true && (EC$('#is_subscriptionT').is(":checked") === true || EC_FRONT_JS_CONFIG_SHOP.bIsUseRegularDelivery === 'T')) {
        EC$(sActionButtonSelector).hide();
        EC$(sReserveSelector).hide();
        EC$(sActionButtonRegular).removeClass("displaynone").show();
    } else {
        EC$(sActionButtonRegular).hide();
    }
};

/**
 * 총 상품금액에 참조화폐 추가
 * @param iTotalPrice
 * @param sQuantityString
 */
function setTotalPriceRef(iTotalPrice, sQuantityString)
{
    var sPrePrice = '';
    var sPostPrice = '';
    var sTotalPrice = SHOP_PRICE_FORMAT.toShopPrice( iTotalPrice );
    var sTotalPriceRef = SHOP_PRICE_FORMAT.shopPriceToSubPrice(iTotalPrice);

    if (sTotalPriceRef == '') {
        return;
    }

    // ECHOSTING-58174
    if (sIsDisplayNonmemberPrice == 'T') {
        sTotalPrice = sNonmemberPrice;
        sTotalPriceRef = sNonmemberPrice;
    }

    var sTotalPriceSelector = oSingleSelection.getTotalPriceSelector();
    if (EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
        if (currency_disp_type == 'P') {
            EC$(sTotalPriceSelector).find('strong').append(' / ' + sTotalPriceRef);
        } else {
            EC$(sTotalPriceSelector).html('<strong class="price">'+ sTotalPriceRef +' '+sQuantityString + '</strong> / ' + sTotalPrice);
        }

        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer(true) === true) {
            parent.EC$(sTotalPriceSelector).find('strong').append(' / ' + sTotalPriceRef);
        }
        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isExistLayer() === true) {
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer() === false) {
                EC$("#productOptionIframe").contents().find(sTotalPriceSelector).find('strong').append(' / ' + sTotalPriceRef);
            }
        }
    } else {
        if (currency_disp_type == 'P') {
            EC$(sTotalPriceSelector).append(' / ' + sTotalPriceRef );
        } else {
            EC$(sTotalPriceSelector).html('<strong><em>' + sTotalPriceRef + '</em></strong> ' + sQuantityString + '</span> / ' + sTotalPrice);
        }
    }
}

/**
 * 부가세 표시 문구 설정 반영
 * @param int iTotalPrice 총 상품 금액
 */
function setProductPriceTaxTypeText(iTotalPrice)
{
    var oProductTaxTypeText = TotalAddSale.getProductTaxTypeText();
    if (typeof(oProductTaxTypeText) === 'undefined') {
        return;
    }

    var iTotalOrderPrice = TotalAddSale.getTotalOrderPrice();
    iTotalPrice = SHOP_PRICE.toShopPrice(iTotalPrice);
    var iTaxPrice = (oProductTaxTypeText.product_tax_type_per > 0) ? SHOP_PRICE.toShopPrice(iTotalOrderPrice - iTotalPrice) : 0;
    if (iTotalPrice == 0) {
        return;
    }

    var iProductPrice = (oProductTaxTypeText.display_prd_vat_separately === 'T') ? iTotalOrderPrice : iTotalPrice;
    var iProductVatPrice = iTotalPrice;
    // 부가세율 공식
    if (oProductTaxTypeText.display_prd_vat_separately === 'F' || oProductTaxTypeText.product_tax_type !== 'A') {
        iTaxPrice = (iProductPrice * oProductTaxTypeText.product_tax_type_per) / (100 + oProductTaxTypeText.product_tax_type_per);
        var iShopDecimal = (oProductTaxTypeText.shop_decimal_place > 0) ? oProductTaxTypeText.shop_decimal_place : 1;
        iTaxPrice = Math.round(iTaxPrice * iShopDecimal) / iShopDecimal;
        iProductVatPrice = iProductVatPrice - iTaxPrice;
    }

    // 부가세가 0원 미만 및 판매가가 0원 이하 이면 부가세 발생 불가
    if (iTaxPrice < 0 || iProductPrice <= 0 || iProductVatPrice <= 0) {
        return;
    }

    var sTaxPrice = SHOP_PRICE_FORMAT.toShopPrice(iTaxPrice);
    var sProductPrice = SHOP_PRICE_FORMAT.toShopPrice(iProductPrice);
    var sProductVatPrice = SHOP_PRICE_FORMAT.toShopPrice(iProductVatPrice);

    var sProductTypeText = oProductTaxTypeText.product_tax_type_text.replace(/\[:제외금액:\]|\[:VAT_EXCLUDED_PRICE:\]/g, sProductVatPrice);
    sProductTypeText = sProductTypeText.replace(/\[:포함금액:\]|\[:VAT_INCLUDED_PRICE:\]/g, sProductPrice);
    sProductTypeText = sProductTypeText.replace(/\[:부가세:\]|\[:VAT:\]/g, sTaxPrice);

    //Tags
    var sTags = 'font-size:' + parseInt(oProductTaxTypeText.product_tax_type_text_font_size, 10) + 'px;';
    sTags += 'color:' + oProductTaxTypeText.product_tax_type_text_color + ';';
    sTags += oProductTaxTypeText.product_tax_type_text_font_type;

    sProductTypeText = ' <span style="' + sTags + '">' + sProductTypeText + '</span>';

    if (EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
        EC$('#totalProducts .total').find('strong').append(sProductTypeText);

        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer(true) === true) {
            parent.EC$('#totalProducts .total').find('strong').append(sProductTypeText);
        }
        if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isExistLayer() === true) {
            if (EC_SHOP_FRONT_PRODUCT_OPTIONLAYER.isDisplayLayer() === false) {
                EC$("#productOptionIframe").contents().find('#totalProducts .total').find('strong').append(sProductTypeText);
            }
        }
    } else {
        EC$('#totalProducts .total').append(sProductTypeText);
    }
}


/**
 * 상품금액 계산 (모바일 및 할인판매가 체크)
 * @param iQuantity 수량
 * @param iQuantity 가격
 * @param sItemCode 옵션코드
 * @param bSoldout 품절여부
 * @param fCallback 콜백함수
 */
function getProductPrice(iQuantity, iOptionPrice, sItemCode, bSoldOut, fCallback)
{
    var fProductPrice = SHOP_PRICE.toShopPrice(product_price);
    if (typeof(iQuantity) == 'undefined' || iQuantity == 0) {
        iQuantity = 1;
    }
    // 1+N이벤트의 경우
    iEventQuantity = EC_SHOP_FRONT_PRODUCT_DEATAIL_BUNDLE.getQuantity(iQuantity, iProductNo);
    fProductPrice = iOptionPrice * parseInt(iEventQuantity, 10);
    oProductList = TotalAddSale.getProductList();
    TotalAddSale.setSubscriptionParam();
    if (EC_SHOP_FRONT_NEW_OPTION_EXTRA_FUNDING.sCurrentCompositionCode !== null) {
        TotalAddSale.setParam('composition_code', EC_SHOP_FRONT_NEW_OPTION_EXTRA_FUNDING.sCurrentCompositionCode);
        var iFundingNum = EC$('.EC-funding-checkbox[value="'+ EC_SHOP_FRONT_NEW_OPTION_EXTRA_FUNDING.sCurrentCompositionCode+'"]').data('funding-no');
        TotalAddSale.setParam('funding_no', iFundingNum);
    }

    // 할인판매가
    if (sItemCode != 'undefined' && sItemCode != '' && sItemCode != '*' && sItemCode != '**' && sItemCode !== null) {
        // 옵션이 있는 경우에는 iOptionPrice가 판매가로 들어가 있어서
        // 할인된 금액이 처리되지 않지만 옵션이 없는 경우 이쪽으로 판매가가 할인 판매가로 설정되어버림
        // 상품 상세페이지내에서는 할인 판매가로 컨트롤 없음
        //fProductPrice = SHOP_PRICE.toShopPrice(product_sale_price);
        // 품절시 ajax호출안함
        TotalAddSale.setProductOptionType(sItemCode, sOptionType);
        TotalAddSale.setSoldOutFlag(bSoldOut);
        TotalAddSale.setQuantity(sItemCode, iQuantity);
        TotalAddSale.setParam('product', oProductList);
        if (has_option === 'F') {
            iQuantity = iEventQuantity;
        }
        TotalAddSale.getCalculatorSalePrice(fCallback, iOptionPrice * parseInt(iQuantity, 10));
    } else {
        if (bSoldOut) {
            TotalAddSale.setQuantity(sItemCode, 0);
            TotalAddSale.setParam('product', oProductList);
        }
        fCallback(fProductPrice);
    }

    return fProductPrice;
}

/**
 * 이벤트 리스너 Object를 파라미터로 받아 추가입력옵션 길이 체크
 * @param oObj
 * @param sVal
 * @param iLimit
 */
function addOptionWordWithObj(oObj, sVal, iLimit)
{
    var iStrLen = sVal.length;
    if (iStrLen > iLimit) {
        alert(sprintf(__('메시지는 %s자 이하로 입력해주세요.'), iLimit));
        oObj.val(sVal.substr(0, sVal.length-1));
        return;
    }
    oObj.parent().parent().find('.length').html(iStrLen);
}

/**
 * 추가입력옵션 길이 체크
 * @param oObj
 * @param limit
 */
function addOptionWord(sId, sVal, iLimit)
{
    // 영문,한글 상관없이 iLimit 글자만큼 제한하도록 수정 (ECHOSTING-78226)
    //var iStrLen = stringByteSize(sVal);
    addOptionWordWithObj(EC$('#'+sId), sVal, iLimit);
}

/**
 * 문자열을 UTF-8로 변환했을 경우 차지하게 되는 byte 수를 리턴한다.
 */
function stringByteSize(str)
{
    if (str == null || str.length == 0) return 0;
    var size = 0;
    for (var i = 0; i < str.length; i++) {
      size += charByteSize(str.charAt(i));
    }
    return size;
}

/**
 * 글자수 체크
 * @param ch
 * @returns {Number}
 */
function charByteSize(ch)
{
    if (ch == null || ch.length == 0 ) return 0;
    var charCode = ch.charCodeAt(0);
    if (escape(charCode).length > 4 ) {
        return 2;
    } else {
        return 1;
    }
}

/**
 * 기존의 SHOP_PRICE_FORMAT.toShopPrice() 의 래핑 함수
 * @param fPrice 옵션 추가 금액
 * @returns String 옵션 추가 금액(금액이 0보다 클경우 '+' 태그 추가)
 */
function getOptionPrice(fPrice)
{
        var sPricePlusTag = '';

        if (fPrice > 0) {
            sPricePlusTag = '+';
        } else {
            sPricePlusTag = '-';
            fPrice = Math.abs(fPrice);
        }

        var aFormat = SHOP_CURRENCY_FORMAT.getShopCurrencyFormat();
        var sPrice = SHOP_PRICE.toShopPrice(fPrice, true);
        return sPricePlusTag + aFormat.head + sPrice + aFormat.tail;
}

/**
 * 추가구성상품 여부 판단후 최종금액 산출
 * @param string sTotalSalePrice 총 상품 금액
 * @param int iTotalSalePrice 판매가
 * @returns string 추가구성 총 상품금액
 */
function getAddProductExistTotalSalePrice(iTotalSalePrice)
{
     EC$('.add_product_option_box_price').each(function(){
         iTotalSalePrice += parseFloat(EC$(this).val());
     });

     return SHOP_PRICE_FORMAT.toShopPrice( iTotalSalePrice );
}

/**
 * 상품상세페이지 기존 모듈 제거하고 신규 모듈로 (ajax)
 * coupon_productdetail_new.html 에 쿠폰다운로드 신규모둘을 추가하여 ajax처리
 */
function getPrdDetailNewAjax()
{
    var sPath = document.location.pathname;

    if (EC_UTIL.trim(parent.EC$('.ec-product-couponAjax').html()) !== "") {
        return;
    }

    EC$.get('/product/coupon_productdetail_new.html',{'product_no' : iProductNo,'cate_no' : iCategoryNo, 'sPath' : sPath} ,function(sHtml){
        parent.EC$('.ec-product-couponAjax').html(sHtml);
        parent.EC$('.ec-product-couponAjax').show();

        EC$('div.eToggle .title').click(function(){
            var toggle = EC$(this).parent('.eToggle');
            if (toggle.hasClass('disable') == false){
                EC$(this).parent('.eToggle').toggleClass('selected');
            }
        });
    });
}

var SELECTEDITEM = {
    iSequence : 0,
    sElementIdPrefix : 'option_box',
    getElementId : function()
    {
        return this.sElementIdPrefix+this.getSequence();
    },
    getSequence : function()
    {
        return this.iSequence++;
    }
};

var EC_SHOP_FRONT_PRODUCT_INFO = {
    getTotalQuantity : function()
    {
        var sQuantitySelector = 'input[name="quantity_opt[]"]';
        var sQuantityContext = (has_option === 'F' ? '' : '#totalProducts');
        if (EC$('.EC-funding-checkbox').length > 0) {
            sQuantitySelector = 'input.quantity';
            sQuantityContext = '.xans-product-funding';
        }
        var iTotalCount = 0;
        EC$(sQuantitySelector, sQuantityContext).each(function() {
            var iQuantity = EC$(this).val();
            if (typeof(EC$(this).attr('composition-code')) !== 'undefined') {
                iQuantity = 0;
                var sCompositionCode = EC$(this).attr('composition-code');
                if (EC$('.selected-funding-item.option_box_price[composition-code="'+sCompositionCode+'"]').length > 0) {
                    iQuantity = EC$('.selected-funding-item.option_box_price[composition-code="'+sCompositionCode+'"]').attr('quantity');
                }
            }

            iTotalCount = iTotalCount + parseInt(iQuantity, 10);
        });
        return iTotalCount;
    }
};
