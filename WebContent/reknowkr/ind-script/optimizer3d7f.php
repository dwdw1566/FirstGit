EC$(function()
{
    // 패싯의 검색 엘리먼트 액션
    EC$('#ec-product-searchdata-form .xans-product-searchfilterlist .ec-product-searchdata-form').click(function()
    {
        if (EC_FRONT_PRODUCT_SEARCH_DATA.setFacetSelector(EC$(this)) === false) {
            return false;
        }
        if (EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.isIncludeFixedForm() === true) {
            EC_FRONT_PRODUCT_SEARCH_DATA.setSearchListCheck(EC$(this));
            EC_FRONT_PRODUCT_SEARCH_DATA.setSearchCountShow();
         } else {
           EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();
        }
    });

     // 버튼식 금액 찾기
    EC$('.xans-product-searchfilterlist .ec-product-searchdata-price').click(function()
    {
        EC_FRONT_PRODUCT_SEARCH_DATA.setButtonSearchPrice(EC$(this));
    });

    EC$('#ec-product-searchdata-mobile-button').click(function(){
        EC$(".xans-product-searchdata > form").show();
        EC$("#container").css("height",EC$(".xans-product-searchdata").height());
        EC$('.xans-product-searchdata.typeSlide').addClass('open');

        EC$('.xans-product-searchfilterlist').each(function() {
            var iChecked = EC$(this).find('input.ec-product-searchdata-form:checked').length;
            var iSelected = EC$(this).find('input.ec-product-searchdata-form:selected').length;

            var iWithin = 0;
            var sWithin = EC$(this).find('.withinInput input[type="text"]').val();

            if (typeof(sWithin) !== 'undefined') {
                iWithin = sWithin.length;
            }

            if (iChecked + iSelected + iWithin > 0) {
                EC$(this).addClass('checked');
            }

            if (iChecked > 0) {
                EC$(this).find('input.ec-product-searchdata-form:checked').parent().addClass('selected');
            }

            if (iSelected > 0) {
                EC$(this).find('input.ec-product-searchdata-form:selected').parent().addClass('selected');
            }
        });

        EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.setLayerMoreFilter(false);
    });
    EC$('.xans-product-searchdata .btnClose').click(function(){
        EC$('#ec-product-searchdata-form').hide();
        EC$('#container').css('height','');
        EC$('#ec-product-searchdata-form').find('.xans-product-searchdata.typeSlide').removeClass('open');

        EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.setLayerMoreFilter(true);
    });

    // form input에 걸려있는 submit 기본 이벤트 제거
    EC$(document).on('keyup keypress', '#ec-product-searchdata-form .withinInput input[type="text"]', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    });

    EC$('#ec-product-searchdata-submit').click(function(){
        EC_FRONT_PRODUCT_SEARCH_DATA.bSearchSubmit = false;
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();
    });

    EC$(document).on('click', '.btnSearchDelete', function() {

        if (EC_FRONT_PRODUCT_SEARCH_DATA.bSearchSubmit === true) {
            return false;
        }
        var sStype  = EC$(this).attr('search_type');
        var sSvalue  = EC$(this).attr('search_value');

        if (sStype === 'ePriceSearch') {
            EC$('#ec-product-searchdata-form #ec-product-searchdata-minval').val(0);
            EC$('#ec-product-searchdata-form #ec-product-searchdata-maxval').val(0);
            EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMinPrice = 0;
            EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMaxPrice = 0;
        } else if (sStype === 'eWithinSearch') {
            if (EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.bFixedListForm === true) {
                EC$('#ec-product-fixed-form .xans-product-fixedsearchfilterlist .withinInput input[type="text"]').val('');
            }
            EC$('#ec-product-searchdata-form .xans-product-searchfilterlist .withinInput input[type="text"]').val('');
        } else {
            EC$('#ec-product-searchdata-form .ec-product-searchdata-form.ec_search_selected').each(function() {
                if (sSvalue === EC$(this).attr('sValue')) {
                    EC$(this).removeClass('ec_search_selected');
                    EC$(this).prop('checked',false);
                    return false;
                }
            });
            EC$('#ec-product-searchdata-form .ec-product-searchdata-form:checked').each(function() {
                if (sSvalue === EC$(this).val()) {
                    EC$(this).removeClass('ec_search_selected');
                    EC$(this).prop('checked', false);
                    return false;
                }
            });
        }

        EC_FRONT_PRODUCT_SEARCH_DATA.bSearchSubmit = false;
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();

    });

    EC$('.ec-product-searchdata-reset').click(function() {
        EC$('#ec-product-searchdata-form .ec-product-searchdata-form.ec_search_selected').removeClass('ec_search_selected');
        EC$('#ec-product-searchdata-form .ec-product-searchdata-form:checked').prop('checked', false);
        EC$('.xans-product.xans-product-searchoption').removeClass('selected');
        EC$('.xans-product-searchprice li').removeClass('selected');
        EC$('#ec-product-searchdata-form #ec_mobile_brand_0').removeClass('checked');
        EC$('#ec-product-searchdata-form #ec_mobile_price_0').removeClass('checked');

        EC$('#ec-product-searchdata-form #ec-product-searchdata-minval').val('');
        EC$('#ec-product-searchdata-form #ec-product-searchdata-maxval').val('');
        EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMinPrice = 0;
        EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMaxPrice = 0;

        EC$('#ec-product-searchdata-form .xans-product-searchfilterlist .withinInput input[type="text"]').val('');

        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();
    });

    EC$('.btn_order').click(function() {
        EC$('#order_by').val(EC$(this).attr('rel'));
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();
    });

    // 검색옵션 버튼 클릭시 레이어팝업 노출
    EC$(".btnSearchOption").click(function(){
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchLayerShow();
    });
    // 검색옵션 버튼 클릭시 레이어팝업 노출
    EC$("#ec-product-searchoption > .close").click(function(){
        EC$("#ec-product-searchoption").hide();
    });

    // 검색옵션 버튼 클릭시 레이어팝업 submit
    EC$("#ec-product-searchdata-layer-submit").click(function(){
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchLayerSubmit();
    });

    // 검색옵션 버튼 클릭시 레이어팝업 reset
    EC$("#ec-product-searchdata-layer-reset").click(function(){
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchLayerShow();
        EC$('#ec-product-searchdata-detail-type').prop('checked', false);
        EC$('#ec-product-searchdata-except-keyword').val('');
    });

    EC$("#ec-product-searchdata-submit_button").click(function(){
        if (EC_UTIL.trim(EC$('#ec-product-searchdata-keyword').val()) === '') {
            alert(__('검색어를 입력해주세요'));
            EC$('#ec-product-searchdata-keyword').focus();
            return false;
        }
    });

    EC$('#order_by').change(function() {
        var aUrl = location.href.split('?');
        if (EC$(this).find('option:selected').val()) {
            if (aUrl[1].indexOf('order_by') == -1) {
                location.href = location.href+'&order_by='+EC$(this).find('option:selected').val();
            } else {
                aParam = aUrl[1].split('&');
                for (var x in aParam) {
                    if (aParam[x].indexOf('order_by') > -1) {
                        aParam[x] = 'order_by='+EC$(this).find('option:selected').val();
                    }
                }
                location.href = aUrl[0] + '?' + aParam.join('&');
            }
        }
    });

    //
    EC$("#ec-product-searchdata-detail-keyword").off('keypress.ec-keyword-event');
    EC$("#ec-product-searchdata-detail-keyword , #ec-search-except-keyword").keypress(function(e) {
        if (e.keyCode == 13 ) {
            EC_FRONT_PRODUCT_SEARCH_DATA.setSearchLayerSubmit();
        }
    });
    // order by set
    EC$("#ec-product-searchdata-searchorderby").change(function()
    {
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit(EC$(this).val());
    });

    // 상세검색 sidebar 슬라이딩
    EC$("#ec-searchdata-button").click(function(){
        var oTarget = EC$(this).parent();
        var oAttrbute = new Object();
        var dWidth = '0';

        if (oTarget.hasClass('opened') === true) {
            dWidth = '-231px';
        }
        if (EC$('#searchSidebar').hasClass('gRight') === true) {
            oAttrbute.right = dWidth;
        } else {
            oAttrbute.left = dWidth;
        }

        oTarget.animate(oAttrbute);

        if (oTarget.hasClass('opened') === true) {
            oTarget.removeClass("opened");
        } else {
            oTarget.addClass("opened");
        }
    });
    // 상세검색 제목 클릭시 토글 액션
    EC$(".ec-searchdata-option-title").click(function(){
        if (EC$('#searchContent').length > 0 ) {
            //EC$(this).parent().parent().parent().parent().toggleClass("selected");
            EC$('.xans-product.xans-product-searchfilterlist').eq(EC$(this).attr('filterNumber')).toggleClass("selected");

        } else {
            EC$(this).parent().toggleClass("selected");
        }

    });
    // 검색폼 내 버튼 클릭시 selected 클래스 추가
    EC$(".ec-searchdata-option-button, .ec-searchdata-colorchip").click(function(){
        if (EC$(this).parent().hasClass("disabled") == false){
            EC$(this).parent().toggleClass("selected");
        }
    });
    // 상품판매가격 버튼 클릭시 selected 클래스 추가
    EC$(".ec-searchdata-price-button").click(function(){
        EC$(this).parent().siblings().removeClass('selected');
        EC$(this).parent().addClass('selected');
    });

    EC$('#ec-product-searchdata-minval , #ec-product-searchdata-maxval').blur(function(){
        if (EC$(this).val() !== '' && EC_FRONT_PRODUCT_SEARCH_DATA.isDecimal2(EC$(this).val()) === false) {
            alert(__('금액은 숫자만 입력가능합니다.'));
            EC$(this).val('');
            return false;
        }

        if (EC$(this).attr('id') === 'ec-product-searchdata-minval') {
            EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMinPrice = EC$(this).val();
        } else {
            EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMaxPrice = EC$(this).val();
        }

        EC$(this).val(SHOP_PRICE.toShopPrice(EC$(this).val(), false));
    });

    EC_FRONT_PRODUCT_SEARCH_DATA.setSearchinit();
    EC_FRONT_PRODUCT_SEARCH_DATA_SLIDE.setSearchSlideinit();

    // 상품판매가격 버튼 클릭시 selected 클래스 추가
    EC$(".btnAllExtend").click(function(){
        EC_FRONT_PRODUCT_SEARCH_DATA.setOpenFilterList(EC$(this));
    });

    // 결과 내 검색 Enter 이벤트
    EC$('.xans-product-searchfilterlist .withinInput input.keyword').keypress(function(e) {
        if (e.keyCode === 13 && EC$(this).attr('onkeyup') !== '') {
            EC$('.withinInput button.btnResearch').click();
        }
    });

    // 결과 내 검색 자동완성 레이어 클릭 처리
    EC$(document).on('click', '.xans-product-searchfilterlist .suggest li', function() {
        EC$(this).closest('.withinInput').find('input[type="text"]').val(EC$(this).text());
        EC$(this).closest('ul').hide();

        if (typeof(mobileWeb) === 'undefined' || mobileWeb !== true) {
            EC$('.withinInput button.btnResearch').click();
        }
    });

    // 결과 내 검색 버튼 클릭 시 처리
    // 값이 비어있으면 alert 없이 아무것도 처리하지 않음
    EC$('.withinInput button.btnResearch').click(function() {

        var sWithin = EC$(this).closest('.withinInput').find('input[type="text"]').val();

        if (typeof(sWithin) !== 'undefined') {
            // 앞/뒤 공백 및 멀티 스페이스 제거
            sWithin = EC_UTIL.trim(sWithin.replace(/\s{2,}/g, ' '));

            if (sWithin !== '') {
                // 실제로 값이 존재하는 경우에 처리
                // 셀럭터는 btnResearch + withinInput 등이 세트로 묶여있기 때문에 별도의 ID로 지정할 필요 없음
                EC$(this).closest('.withinInput').find('input[type="text"]').val(sWithin);

                // IE에서 이벤트 타이밍 문제로 실제 input의 값이 바뀌기 전에 submit 되는 버그가 있어 타임아웃 설정
                setTimeout(function() {
                    EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();
                }, 100);
            }
        }
    });
});

// window load 이후 검색 레이어 스크롤 처리
EC$(window).on('load', function() {
    EC_FRONT_PRODUCT_SEARCH_DATA.setScrollCheck();
});

// window resize 이후 검색 레이어 스크롤 처리
EC$(window).resize(function() {
    EC_FRONT_PRODUCT_SEARCH_DATA.setScrollCheck();
});

var EC_FRONT_PRODUCT_SEARCH_DATA =
{
    bSearchSubmit : false,
    bEmptyElasticData : false,
    bUseSearch : 'F',
    iSearchSetMinPrice : 0,
    iSearchSetMaxPrice : 0,
    iSearchFormCount : 0,
    sShowOpen : 'F',
    sFilterShowNumList : '',
    aOpenFilterElement : [],
    /**
     * EC JAPAN에서 EC_FRONT_PRODUCT_SEARCH_DATA.sShowOpen 값을 세팅
     */
    setShowOpen : function()
    {
        if (this.getCommonObjectValue('is_facet_open') !== true) {
            return;
        }
        this.sShowOpen = 'T';
    },
    /**
     * EC JAPAN에서 EC_FRONT_PRODUCT_SEARCH_DATA.aOpenFilterElement 값을 세팅
     */
    setFilterShowNumList : function()
    {
        var aOpenFacetElement = this.getCommonObjectValue('open_facet_element');
        if (aOpenFacetElement === null) {
            return;
        }
        if (aOpenFacetElement.length === 0) {
            return;
        }
        this.aOpenFilterElement = this.getCommonObjectValue('open_facet_element');
    },
    /**
     * EC JAPAN에서 EC_FRONT_PRODUCT_SEARCH_DATA.bUseSearch 값을 세팅
     */
    setUseSearch : function()
    {
        if (this.getCommonObjectValue('V') === 6) {
            this.bUseSearch = this.getCommonObjectValue('bIsFacetSelected') === true ? 'T' : 'F';
        }
    },
    /**
     * EC JAPAN에서 Elastic 검색을 사용하지 않는데 디자인 반영한 경우 불필요한 영역을 제거
     */
    setSearchOptionButton : function()
    {
        if (this.getCommonObjectValue('V') === 6) {
            if (EC_FRONT_PRODUCT_SEARCH_DATA.getCommonObjectValue('bIsElasticInUse') === true) {
                return true;
            }
            EC$('#ec-product-searchdata-mobile-button').remove();
            EC$('#ec-product-searchoption').remove(); // 검색옵션 레이어
            EC$('#ec-product-searchdata-searchkeyword_form').find('.btnSearchOption').remove(); // 검색옵션 버튼
        } else {
            if (typeof(bUseElastic) === 'undefined' || bUseElastic === false) {
                EC$('#ec-product-searchdata-searchkeyword_form').find('.btnSearchOption').remove();
            }
        }
    },
    /**
     * Facet을 제거해야하는지 판단하여 제거
     */
    setFacetClear : function()
    {
        if (this.getCommonObjectValue('V') !== 6) {
            return true;
        }
        var bIsFacetClear = this.getCommonObjectValue('bIsFacetClear');
        if (bIsFacetClear === true) {
            this.setNoDataList();
            EC$('#ec-product-searchdata-mobile-button').remove();
            EC$('.xans-product.xans-product-searchfilterlist').remove();
            EC$('#ec-searchdata-area').remove();
        }
        if (EC_FRONT_PRODUCT_SEARCH_DATA.getCommonObjectValue('bIsElasticInUse') !== true) {
            EC$('#ec-product-searchdata-mobile-button').remove();
        }
    },
    /**
     * 초기화
     */
    setSearchinit : function() {
        this.setShowOpen();
        this.setFilterShowNumList();
        this.setSearchOptionButton();
        this.setFacetClear();
        this.setUseSearch();
        EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.setSearchFixListinit();
        if (EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.isIncludeFixedForm() === true) {
            if (this.bUseSearch === 'T') {
                EC$('.xans-product-searchconditiondata').show();
            }

            if (EC$('.xans-product-searchconditiondata').length > 0) {
                EC$('#ec-product-searchdata-mobile-button').addClass('checked');
            }

            EC$('.xans-product.xans-product-searchfilterlist').each(function() {
                if (EC$(this).find('.ec-product-searchdata-form.ec_search_selected').length > 0 ) {
                    var oTargetObject = EC$(this).find('.ec-product-searchdata-form.ec_search_selected').eq(0);
                    EC_FRONT_PRODUCT_SEARCH_DATA.setSearchListCheck(oTargetObject);
                }
            });

            if (EC$('.ec-product-searchdata-price.ec_search_selected').length > 0 ) {
                EC_FRONT_PRODUCT_SEARCH_DATA.setSearchListCheck(EC$('.ec-product-searchdata-price.ec_search_selected').eq(0));
            }

            EC$('.xans-product-searchdata').find('button.btnDelete').on('click', function() {
                if (EC$(this).closest('.withinInput').length === 0) {
                    var oForm = EC$(this).parents('form');
                    EC$('#ec-product-searchdata-keyword').val('').focus();
                    EC$('body').removeClass("eMobilePopup"); // eMobilePopup 클래스 삭제
                    EC$('body').css("width", ""); // width:100% 삭제
                    oForm.find('#ec-product-searchdata-keyword_drop').hide();
                    oForm.find('#ec-product-searchdata-auto-list').hide();
                }
            });

            EC$('.xans-product-searchfilterlist .withinInput .btnDelete').click(function() {
                EC$(this).closest('.withinInput').find('input[type="text"]').val('').focus();
                EC$(this).closest('.xans-product-searchfilterlist').removeClass('checked');
            });


            EC$('#ec-product-searchdata-use').click(function() {
                EC$(".xans-product-searchdata > form").show();
                EC$(this).addClass('checked');
                EC$("#container").css("height",EC$(".xans-product-searchdata").height());
            });
            if (this.bEmptyElasticData === true) {
                EC$('#ec-product-searchdata-mobile-button').remove();
            }
        } else {
            if (this.bEmptyElasticData === true) {
                EC$('.xans-product.xans-product-searchfilterlist').remove();
                EC$('#ec-searchdata-area').remove();
            } else {
                // 고정형 열기 닫기
                if (EC$('#searchContent').length > 0 ) {
                    this.setExtendButton();

                    if (this.sShowOpen === 'T' ) {
                        EC$('.btnAllExtend').addClass('open');
                    }
                }
            }

            // 좌측/우측 슬라이딩 형일때만
            if (mobileWeb === false && EC$('#searchSidebar').length > 0) {
                EC$('.xans-product.xans-product-searchfilterlist.selected').each(function () {
                    EC_FRONT_PRODUCT_SEARCH_DATA.iSearchFormCount += EC$(this).find('button').length;
                });
            }

            if (EC$('.xans-product.xans-product-searchfilterlist').length < 6) {
                EC$('.btnAllExtend').hide();
            }
        }
        EC$('.ec-product-searchdata-form-clear').click(function()
        {
            var sKeyValue = EC$(this).attr('filter_key_value');
            var sSelectedFilterKey = 'ec-product-searchdata-' + sKeyValue;
            var sSelectFilterFormClass = 'ec-product-searchdata-form';
            var oTarget = EC$('#ec-product-searchdata-form .' + sSelectedFilterKey).parent();
            if (sKeyValue === 'price') {
                EC$('#ec-product-searchdata-form #ec-product-searchdata-minval').val('');
                EC$('#ec-product-searchdata-form #ec-product-searchdata-maxval').val('');
                EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMinPrice = 0;
                EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMaxPrice = 0;
                sSelectFilterFormClass = 'ec-product-searchdata-price';

                EC_FRONT_PRODUCT_SEARCH_DATA_SLIDE.setSearchClear();
            }
            if (oTarget.find('.' + sSelectFilterFormClass).prop('type') ===  'checkbox') {
                oTarget.find('.' + sSelectFilterFormClass).each(function() {
                    if (EC$(this).is(':checked') === true) {
                        EC$(this).click();
                    }
                });
            } else {
                oTarget.find('.xans-product-filterform li').removeClass('selected');
                oTarget.find('.xans-product-filterform .ec-product-searchdata-form').removeClass('ec_search_selected');
            }

            oTarget.removeClass('checked');

            EC_FRONT_PRODUCT_SEARCH_DATA.setSearchCountShow();
        });
    },
    /**
     * 버튼식 금액 찾기
     * @param object 선택된 object
     */
    setButtonSearchPrice : function(oSearchPirce)
    {
        EC$('#ec-product-searchdata-form  .ec-product-searchdata-price').parent().find('.xans-product-filterform  li').removeClass('selected');

        oSearchPirce.parents('li').addClass('selected');

        if (oSearchPirce.hasClass('ec_search_selected') === true) {
            this.iSearchSetMinPrice = null;
            this.iSearchSetMaxPrice = null;

            this.setSearchSubmit();
        }

        var iMinPrice = oSearchPirce.attr('min_price');
        var iMaxPrice = oSearchPirce.attr('max_price');


        EC$('#ec-product-searchdata-form  #ec-product-searchdata-minval').val(SHOP_PRICE.toShopPrice(iMinPrice, false));
        EC$('#ec-product-searchdata-form  #ec-product-searchdata-maxval').val(SHOP_PRICE.toShopPrice(iMaxPrice, false));

        this.iSearchSetMinPrice = iMinPrice;
        this.iSearchSetMaxPrice = iMaxPrice;

        if (EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.isIncludeFixedForm() === true) {
            this.setSearchListCheck(oSearchPirce);
            EC_FRONT_PRODUCT_SEARCH_DATA.setSearchCountShow();
        } else {
            this.setSearchSubmit();
        }

    },
    /**
     * 실제 submit처리
     */
    setSearchSubmit : function(sSortType, sFormName) {

        if (this.bSearchSubmit === true) {
            return false;
        }

        if (sFormName === undefined || sFormName === '') {
            sFormName = '#ec-product-searchdata-form ';
        }
        this.setFacetFormParam(sFormName, sSortType);

        // 중복 클릭 방지
        this.bSearchSubmit = true;
        if (mobileWeb === true) {
            EC$('.xans-product-searchdata > form').hide();
        }

        // pc fixed form
        this.setTopFixedSearchForm();

        var sPathName = location.pathname;
        var bSearchAll = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('search_all');
        // 메인페이지에서 패싯 필터링 변경시 검색 페이지로 이동
        if ((sPathName === '/' || sPathName === '/index.html') || bSearchAll === 'T') {
            sPathName = '/product/search.html';
            EC$("#ec-product-searchdata-form").append('<input type="hidden" name="search_all" value="T">');
        }

        EC$('#ec-product-searchdata-form').attr('action', sPathName);
        EC$('#ec-product-searchdata-form').submit();
    },
    /**
     * 소숫점 둘째자리 체크
     */
    isDecimal2 : function(value){
        if (value == '') return false;
        return (/^[0-9]+(\.[0-9]{1,2})?$/).test(value);
    },

    setSearchListCheck : function (oSearchId)
    {
        var sValue = oSearchId.attr('sValue');

        if (oSearchId.prop('type') === 'checkbox') {
            sValue = oSearchId.val();
        }

        if (sValue === '' || typeof(sValue) === 'undefined') {
            return;
        }

        var aFilterDataValue = sValue.split('=');
        var sSelectedFilterKey = 'ec-product-searchdata-' + aFilterDataValue[0];
        var oTarget = EC$('.' + sSelectedFilterKey).parent();

        if (aFilterDataValue[0] === 'price') {
            oTarget = EC$('.title.' + sSelectedFilterKey).parent();
        }

        if (oSearchId.hasClass('circle') === true) {
            if (oSearchId.find('.icoColorCheck').length === 0) {
                oSearchId.html('<span class="icoColorCheck"></span>');
            }
        }

        var bCheckSelected = false;

        if (oSearchId.closest('.xans-product-filterform').find('input:checkbox').length > 0) {
            if (oSearchId.closest('.xans-product-filterform').find('label.selected').length > 0) {
                bCheckSelected = true;
            }
        } else {
            if (oSearchId.closest('.xans-product-filterform').find('li.selected').length > 0) {
                bCheckSelected = true;
            }
        }
        if (bCheckSelected === true) {
            oTarget.addClass('checked');
        } else {
            oTarget.removeClass('checked');
        }
    },
    // 모바일 더보기 용
    setSearchPriceData : function () {

        var iMinPriceValue = this.iSearchSetMinPrice;
        var iMaxPriceValue = this.iSearchSetMaxPrice;
        var bPriceCheck = false;
        if (iMinPriceValue !== 0 || iMaxPriceValue !== 0 ) {
            bPriceCheck = true;
        }

        if (bPriceCheck === true) {
            EC$('#ec-product-searchdata-minval').val(iMinPriceValue);
            EC$('#ec-product-searchdata-maxval').val(iMaxPriceValue);
        }
    },
    /**
     * facet처리시
     */
    setSearchCountShow : function()
    {
        if (EC$('#ec-product-searchdata-search-count').length  <= 0) {
            return;
        }
        if (this.bSearchSubmit === true) {
            return;
        }
        EC$('#ec-product-searchdata-form input:hidden[name^="search_form[option_data]"]').each(function() {
            EC$(this).remove();
        });

        var sAction =  '/exec/front/product/Searchcount';

        this.setFacetFormParam('#ec-product-searchdata-form ');

        if (EC$('#ec-product-searchdata-catenum').val() === '') {
            var iCateNum = EC_FRONT_PRODUCT_SEARCH_DATA.getCommonObjectValue('iCatgoryNum');
            EC$('#ec-product-searchdata-catenum').val(iCateNum);
        }

        EC$.post(sAction, EC$('#ec-product-searchdata-form').serialize(), function(aResultJson)
        {
            var sMsg =  __('SEARCH.COUNT', 'SHOP.FRONT.NEW.PRODUCT.SEARCHDATA').replace('$s', EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.iLayerFormTotalCount);
            if (aResultJson['bPassed'] === true) {
                sMsg =  __('SEARCH.COUNT', 'SHOP.FRONT.NEW.PRODUCT.SEARCHDATA').replace('$s', aResultJson['iCount']);
            }
            EC$('#ec-product-searchdata-submit').html(sMsg);

        }, 'json');

        this.bSearchSubmit = false;
    },
    /**
     * 실제 submit처리
     */
    setSearchLayerSubmit : function() {

        var sKeyword = EC$('#ec-product-searchdata-detail-keyword').val();
        var sExceptKeyword = EC$('#ec-product-searchdata-except-keyword').val();

        if (EC_UTIL.trim(sKeyword) === '') {
            alert(__('검색어를 입력해주세요'));
            EC$('#ec-product-searchdata-detail-keyword').focus();
            return false;
        }
        EC$("#ec-product-searchoption").hide();
        EC$('#ec-product-searchdata-detail-keyword').val(encodeURIComponent(sKeyword));

        if (sExceptKeyword !== '') {
            EC$('#ec-product-searchdata-except-keyword').val(encodeURIComponent(sExceptKeyword));
        }

        EC$('#ec-product-searchdata-keyword').attr('name','');
        EC$('#ec-product-searchdata-detail-keyword').attr('name','keyword');
        EC$('<input>').attr({
            'type':'hidden',
            'name':'search_from_layer'
        }).val('T').appendTo(EC$('#ec-product-searchdata-searchkeyword_form'));

        EC$('#ec-product-searchdata-searchkeyword_form').attr('action',location.pathname );
        EC$('#ec-product-searchdata-searchkeyword_form').submit();

    },
    setSearchLayerShow : function() {
        var sSearchType = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('search_detail_type');
        var sExceptKeyword = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('except_keyword');

        EC$('#ec-product-searchdata-detail-keyword').val(EC$('#ec-product-searchdata-keyword').val());
        if (sExceptKeyword !== '') {
            EC$('#ec-product-searchdata-except-keyword').val(decodeURIComponent(sExceptKeyword));
        }
        if (sSearchType !== '') {
            EC$('#ec-product-searchdata-detail-type').prop('checked',true);
        }

        EC$("#ec-product-searchoption").show();
    },
    setNoDataList :  function () {
        EC$("#ec-searchdata-button").remove();
    },
    /**
     * 검색 레이어 스크롤 처리
     * CSS로 알아서 처리되므로, 해당 element에 높이 값만 넣어줌
     */
    setScrollCheck: function() {
        var oSearchdataArea = EC$('#searchSidebar #ec-searchdata-area');

        if (oSearchdataArea.length > 0) {
            oSearchdataArea.height(EC$(window).height() - parseInt(oSearchdataArea.css('padding-top'), 10) - parseInt(oSearchdataArea.css('padding-bottom'), 10));
        }
    },
    setOpenFilterList : function (oObject) {
        if (oObject.hasClass('open') === false) {
            EC$('.xans-product.xans-product-searchfilterlist').show();
            this.sShowOpen = 'T';
            this.setExtendButton();
        } else {
            EC$('.xans-product.xans-product-searchfilterlist').each(function(i) {
                if (i > 4) {
                    EC$(this).hide();
                }
            });
            this.sShowOpen = 'F';
        }
        oObject.toggleClass('open');
    },
    setExtendButton : function()
    {
        // 각 항목별 높이값이 37이상인 경우 2줄로 나옴 이때 펼치기버튼 노출 비노출
        EC$('.xans-product.xans-product-searchfilterlist').each(function(i) {
            var oTargetObject = EC$(this).find('.xans-product-filterform');
            if (oTargetObject.height() < 38) {
                oTargetObject.parent().find('.extend').hide(); //('fase');
            } else {
                oTargetObject.parent().find('.extend').show(); //('fase');
                oTargetObject.parent().find('.ec-searchdata-option-title').attr('filterNumber', i);
            }
        });

        if (this.sFilterShowNumList !== '' || this.aOpenFilterElement.length > 0) {
            var aFilterShowList = this.sFilterShowNumList.split(',');
            if (this.aOpenFilterElement.length > 0) {
                aFilterShowList = this.aOpenFilterElement;
            }
            for (var i = 0; i < aFilterShowList.length; i++) {
                if (aFilterShowList[i] !== '') {
                    EC$('.xans-product.xans-product-searchfilterlist').eq(aFilterShowList[i]).toggleClass("selected");
                }
            }
        }

        // 가격 영역별 검색의 경우 xans-product-filterform.content 가 존재하지 않음 이때 슬라이더가 있으면 해당 버튼 삭제필요
        if (EC$('.xans-product.xans-product-searchfilterlist .priceSlide').length > 0) {
            EC$('.xans-product.xans-product-searchfilterlist .priceSlide').find('.extend').hide();
        }

        // 결과 내 검색인 경우에도 무조건 '+' 버튼 삭제
        if (EC$('.xans-product.xans-product-searchfilterlist .searchForm').length > 0) {
            EC$('.xans-product.xans-product-searchfilterlist .searchForm').find('.extend').hide();
        }
    },
    getCommonObjectValue : function(sPropertyName)
    {
        if (typeof(EC_FRONT_JS_CONFIG_CURATION) !== 'object') {
            return null;
        }
        if (EC_FRONT_JS_CONFIG_CURATION.hasOwnProperty(sPropertyName) === false) {
            return null;
        }
        return EC_FRONT_JS_CONFIG_CURATION[sPropertyName];
    },
    setFacetSelector : function(oObjectSelector)
    {
        if (oObjectSelector.prop('type') !== 'checkbox') {
            if (oObjectSelector.parent().hasClass('disabled') === true) {
                return false;
            }
        } else {
            if (oObjectSelector.parent().parent().hasClass('disabled') === true) {
                return false;
            }
        }
        var oTarget = oObjectSelector;
        if (EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.isIncludeFixedForm() === true) {
            oTarget = oObjectSelector.parent();
        }
        if (oTarget.hasClass('ec_search_selected') === true || oTarget.hasClass('selected') ) {
            oTarget.removeClass('selected');
            oObjectSelector.removeClass('ec_search_selected');
            // oObjectSelector.attr('sValue
        } else {
            if (oObjectSelector.hasClass('ec-product-searchdata-price') === false) {
                oTarget.addClass('selected');
            }
            oObjectSelector.addClass('ec_search_selected');
        }

    } ,
    setFacetFilterValue : function(oTargetForm)
    {
        EC$(oTargetForm + '.ec-product-searchdata-form.ec_search_selected').each(function() {
            if (EC$(this).prop('type') !== 'checkbox') {
                var sValueData = EC$(this).attr('sValue');
                if (sValueData !== undefined &&  sValueData !== ''  && EC_FRONT_PRODUCT_SEARCH_DATA.isDuplicateValueData(sValueData) === false) {
                    EC$('<input>').attr({type: 'hidden',name: 'search_form[option_data][]',value: encodeURIComponent(sValueData)}).appendTo('#ec-product-searchdata-form');
                }
            }
        });
    },
    isDuplicateValueData : function(sValue) {
        var bDuplicate = false;
        EC$('#ec-product-searchdata-form input:hidden[name^="search_form[option_data]"]').each(function() {
            if (EC$(this).val() === sValue) {
                bDuplicate = true;
                return false;
            }
        });

        return bDuplicate;
    },

    setFacetFormParam : function(sFormName, sSortType) {

        this.setFacetFilterValue(sFormName);

        var iCateNum = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('cate_no');
        if (EC$('#ec-product-searchdata-catenum').length === 0) {
            EC$("#ec-product-searchdata-form").append('<input type="hidden" name="cate_no" id="ec-product-searchdata-catenum" value="'+ iCateNum + '"/>');
        } else {
            EC$('#ec-product-searchdata-catenum').val(iCateNum);
        }

        var sSortMethod = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('sort_method');
        if (sSortType !== undefined && sSortType !== '') {
            sSortMethod = sSortType;
        }
        if (sSortMethod !== '' && sSortMethod.search('#Product_ListMenu') < 0) {
            sSortMethod = sSortMethod + '#Product_ListMenu';
        }

        if (sSortMethod !== '') {
            EC$("#ec-product-searchdata-form").append('<input type="hidden" name="sort_method" id="ec-product-searchdata-sort_method" value="'+ sSortMethod + '"/>');
        }
        //EC$('#ec-product-searchdata-sort_method').val(sSortMethod);

        var iMinPriceValue = this.iSearchSetMinPrice;
        var iMaxPriceValue = this.iSearchSetMaxPrice;

        if (iMinPriceValue !== 0) {
            EC$(sFormName + '#ec-product-searchdata-minval').val(iMinPriceValue);
        }

        if (iMaxPriceValue !== 0 ) {
            EC$(sFormName + '#ec-product-searchdata-maxval').val(iMaxPriceValue);
        }

        var sExceptKeyword = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('except_keyword');
        var sSearchDetailType = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('search_detail_type');
        var sSearchType = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('search_type');

        if (sExceptKeyword !== '') {
            EC$("#ec-product-searchdata-form").append('<input type="hidden" name="except_keyword" value="'+ sExceptKeyword + '"/>');
        }

        if (sSearchDetailType !== '') {
            EC$("#ec-product-searchdata-form").append('<input type="hidden" name="search_detail_type" value="'+ sSearchDetailType + '"/>');
        }

        if (sSearchType !== '') {
            EC$("#ec-product-searchdata-form").append('<input type="hidden" name="search_type" value="'+ sSearchType + '"/>');
        }

        var sKeywordValue = CAPP_SHOP_FRONT_COMMON_UTIL.getParameterByName('keyword');
        if (EC$('#ec-product-searchdata-keyword').length > 0 ) {
            if (sKeywordValue === ''  && EC$('#ec-product-searchdata-keyword').val() !== '') {
                sKeywordValue = encodeURIComponent(EC$('#ec-product-searchdata-keyword').val());
            }
            EC$('#ec-product-searchdata-keyword_hidden').val(sKeywordValue);
        } else if (sKeywordValue !== '') {
            // 기존 스킨을 사용하는 경우에도 동작 할수 있도록
            EC$('#ec-product-searchdata-keyword_hidden').val(sKeywordValue);
        }

        var sWithin = '';

        if (EC$('.withinInput input[type="text"]:visible').length > 0) {
            sWithin = EC$('.withinInput input[type="text"]:visible').val();
        } else {
            sWithin = EC$('.ec-product-searchdata-within').parent().find('.withinInput input[type="text"]').val();
        }

        if (typeof(sWithin) !== 'undefined') {
            sWithin = EC_UTIL.trim(sWithin.replace(/\s{2,}/g, ' '));

            if (sWithin.length > 0) {
                EC$('#ec-product-searchdata-form').append('<input type="hidden" name="within" value="' + sWithin + '"/>');
            }
        }
    },
    setTopFixedSearchForm : function() {
        if (EC$('#searchContent').length === 0 ) {
            return;
        }
        // 상단형일때 전체 오픈 여부
        EC$("#ec-product-searchdata-form").append('<input type="hidden" name="sShowOpen" value="' + this.sShowOpen + '"/>');

        var sFilterShowNumList = '';
        // 몇번째 필터를 열었는지 보관
        EC$('.xans-product.xans-product-searchfilterlist.selected').each(function() {
            sFilterShowNumList += EC$(this).attr('filterlistnum') + ',';
        });

        if (EC$('.xans-product.xans-product-searchfilterlist.selected').length > 0) {
            EC$("#ec-product-searchdata-form").append('<input type="hidden" name="sFilterShowNumList" value="' + sFilterShowNumList + '"/>');
        }

    }

};
var EC_FRONT_PRODUCT_SEARCH_DATA_SLIDE = {
    iSearchSetMinPrice: 0,
    iSearchSetMaxPrice: 0,
    iSearchParamMinPrice: 0,
    iSearchParamMaxPrice: 0,
    oSlider: null,
    oCloneSlider : null,
    /**
     * V6에서 사용하는 가격 세팅 메소드
     * @param oConfig
     */
    setPriceRangeConfig : function()
    {
        var oPriceRange = EC_FRONT_PRODUCT_SEARCH_DATA.getCommonObjectValue('price_range');
        if (oPriceRange === null) {
            return false;
        }
        this.iSearchSetMinPrice = oPriceRange.range_min;
        this.iSearchSetMaxPrice = oPriceRange.range_max;
        this.iSearchParamMinPrice = oPriceRange.search_min;
        this.iSearchParamMaxPrice = oPriceRange.search_max;
    },

    setSearchSlideinit : function() {
        this.setPriceRangeConfig();
        if (EC$("#eSearchPriceSlider").length > 0 ) {

            var eSearchPriceSlider = document.getElementById('eSearchPriceSlider');
            if (this.iSearchSetMinPrice >  0 || this.iSearchSetMaxPrice >  0) {
                noUiSlider.create(eSearchPriceSlider, {
                    start: [this.iSearchSetMinPrice, this.iSearchSetMaxPrice],
                    connect: true,
                    range: {
                        'min': this.iSearchSetMinPrice,
                        'max': this.iSearchSetMaxPrice
                    }
                });
                this.setInitPriceSet(eSearchPriceSlider, '#ec-product-searchdata-form ');
                this.cloneSlider();
                eSearchPriceSlider.noUiSlider.on('change', function( values, handle ) {
                    EC_FRONT_PRODUCT_SEARCH_DATA_SLIDE.setSlideAction(values, false);
                });
            }
            this.oSlider = eSearchPriceSlider.noUiSlider;
        }
    },
    setInitPriceSet : function (eSearchPriceSlider, oTargetForm) {
        EC$(oTargetForm + '#eMinSlidePriceView').html(SHOP_PRICE_FORMAT.toShopPrice(this.iSearchSetMinPrice));
        EC$(oTargetForm + '#eMaxSlidePriceView').html(SHOP_PRICE_FORMAT.toShopPrice(this.iSearchSetMaxPrice));

        if (this.iSearchParamMinPrice > 0 || this.iSearchParamMaxPrice > 0) {
            eSearchPriceSlider.noUiSlider.set([this.iSearchParamMinPrice, this.iSearchParamMaxPrice]);

            EC$(oTargetForm + '#eMinSlidePriceView').html(SHOP_PRICE_FORMAT.toShopPrice(this.iSearchParamMinPrice));
            EC$(oTargetForm + '#eMaxSlidePriceView').html(SHOP_PRICE_FORMAT.toShopPrice(this.iSearchParamMaxPrice));

            EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMinPrice = this.iSearchParamMinPrice;
            EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMaxPrice = this.iSearchParamMaxPrice;
        }
    },
    setSlideAction : function (aValues, bForceAction) {
        var iMinSlidePirce = aValues[0];
        var iMaxSlidePirce = aValues[1];

        EC$('#eMinSlidePriceView').html(SHOP_PRICE_FORMAT.toShopPrice(iMinSlidePirce));
        EC$('#eMaxSlidePriceView').html(SHOP_PRICE_FORMAT.toShopPrice(iMaxSlidePirce));


        EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMinPrice = iMinSlidePirce;
        EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMaxPrice = iMaxSlidePirce;


        if (bForceAction === false && EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.bFixedListForm === false && (EC_MOBILE === false && EC_MOBILE_DEVICE === false)) {
            bForceAction = true;
        }

        if (bForceAction === true) {
            EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();
        } else {
            EC$('.ec-product-searchdata-price').parent().addClass('checked');
            EC_FRONT_PRODUCT_SEARCH_DATA.setSearchCountShow();
        }
    },
    setSearchClear : function () {
        if (EC$("#eSearchPriceSlider").length > 0 ) {
            this.oSlider.set([this.iSearchParamMinPrice, this.iSearchParamMaxPrice]);
        }
        if (EC$('#ec-product-fixed-slider').length > 0) {
            this.oCloneSlider.set([this.iSearchParamMinPrice, this.iSearchParamMaxPrice]);
        }

    },
    cloneSlider: function () {
        if (EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.bFixedListForm === false || EC$('#ec-product-fixed-slider').length === 0) {
            return;
        }
        var oNewSearchPriceSlider  = document.getElementById('ec-product-fixed-slider');

        noUiSlider.create(oNewSearchPriceSlider, {
            start: [this.iSearchSetMinPrice, this.iSearchSetMaxPrice],
            connect: true,
            range: {
                'min': this.iSearchSetMinPrice,
                'max': this.iSearchSetMaxPrice
            }
        });

        this.setInitPriceSet(oNewSearchPriceSlider, '#ec-product-fixed-form ');

        oNewSearchPriceSlider.noUiSlider.on('change', function( values, handle ) {
            EC_FRONT_PRODUCT_SEARCH_DATA_SLIDE.setSlideAction(values, true);
        });

        this.oCloneSlider = oNewSearchPriceSlider.noUiSlider;
    }

};

var EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM = {
    bFixedListForm : false,
    bFixedFormAction : false,
    bLayerFormInit : false,
    bLayerFormOpen : false,
    iLayerFormTotalCount : 0,
    setSearchFixListinit : function() {
        if (EC$('#ec-product-fixed-form .xans-product-fixedsearchfilterlist').length > 0 && EC$('#ec-product-fixed-form').length > 0) {
            this.bFixedListForm = true;

            // 패싯의 검색 엘리먼트 액션
            EC$('#ec-product-fixed-form .xans-product-fixedsearchfilterlist .ec-product-searchdata-form').click(function()
            {
                if (EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.bLayerFormOpen === true) {
                    EC$('#ec-product-searchdata-form .xans-product-searchfilterlist').find('#'  + EC$(this).attr('id')).click();
                    return false;
                }

                EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.setFixedFormSubmit(EC$(this));
            });
            EC$('#ec-product-fixed-form .xans-product-fixedsearchfilterlist .ec-product-searchdata-price').click(function()
            {
                EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMinPrice = EC$(this).attr('min_price');
                EC_FRONT_PRODUCT_SEARCH_DATA.iSearchSetMaxPrice =  EC$(this).attr('max_price');
                EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.bFixedFormAction = true;
                EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit();
            });

            EC$('#ec-product-fixed-form .xans-product-fixedsearchfilterlist .ec-product-searchdata-form').each(function() {
                if (EC$(this).prop('type') === 'checkbox') {
                    var sObjectId = EC$(this).attr('id');

                    var oLabelObject = EC$('#ec-product-fixed-form .xans-product-fixedsearchfilterlist .ec-product-searchdata-form').find("label[for='" + sObjectId + "']");

                    if (oLabelObject.length > 0 ) {
                        oLabelObject.attr('id' , sObjectId + 'fixed');
                        EC$(this).attr('id' , sObjectId + 'fixed');
                    }
                }
            });

            EC$('#ec-product-fixed-form .xans-product-fixedsearchfilterlist .btnDelete').click(function() {
                EC$(this).closest('.withinInput').find('input[type="text"]').val('').focus();
            });

            var sMsg =  __('SEARCH.COUNT', 'SHOP.FRONT.NEW.PRODUCT.SEARCHDATA').replace('$s', EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.iLayerFormTotalCount);
            EC$('#ec-product-searchdata-submit').html(sMsg);
        }
    },

    setFixedFormSubmit : function(oObject) {
        EC$('#ec-product-searchdata-form input:hidden[name^="search_form[option_data]"]').each(function() {
            EC$(this).remove();
        });
        EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.bFixedFormAction = true;
        EC_FRONT_PRODUCT_SEARCH_DATA.setFacetSelector(oObject);
        EC_FRONT_PRODUCT_SEARCH_FIX_LIST_FORM.setFixedFormData();
        EC_FRONT_PRODUCT_SEARCH_DATA.setSearchSubmit('' , '#ec-product-fixed-form ');
    },

    setFixedFormData : function() {
        if (this.bFixedFormAction === true) {
            EC$('#ec-product-fixed-form .ec-product-searchdata-form:checked').each(function() {
                EC$('<input>').attr({type: 'hidden',name: 'search_form[option_data][]',value: encodeURIComponent(EC$(this).val())}).appendTo('#ec-product-searchdata-form');
            });
        }
    } ,
    isIncludeFixedForm : function() {
        if (this.bFixedListForm === true || EC_MOBILE === true || EC_MOBILE_DEVICE === true) {
            return true;
        }

        return false;
    },
    setLayerMoreFilter : function (bClose) {
        if (this.bFixedListForm === false) {
            return;
        }
        if (bClose === true) {
            EC$('#ec-product-fixed-form-filter').hide();
            this.bLayerFormOpen = false;
        } else {
            EC$('#ec-product-fixed-form-filter').show();

            this.bLayerFormOpen = true;
            if (this.bLayerFormInit === false && EC_FRONT_PRODUCT_SEARCH_DATA.bUseSearch === 'T') {
                EC_FRONT_PRODUCT_SEARCH_DATA.setSearchCountShow();
                this.bLayerFormInit = true;
            }
        }
    },


};

var EC_SHOP_FRONT_NEW_LIKE_BROWSER_CACHE = {
    /**
     * 로컬 스토리지 지원 여부
     * @return bool 지원하면 true, 지원하지 않으면 false
     */
    isSupport: function() {
        if (window.localStorage) {
            return true;
        } else {
            return false;
        }
    },

    /**
     * 로컬 스토리지에 데이터 셋팅
     * @param string sKey 키
     * @param mixed mData 저장할 데이터
     * @param int iLifeTime 살아있는 시간(초) (기본 1일)
     * @return bool 정상 저장 여부
     */
    setItem: function(sKey, mData, iLifeTime) {
        if (this.isSupport() === false) {
            return false;
        }

        iLifeTime = iLifeTime || 86400;

        try {
            window.localStorage.setItem(sKey, JSON.stringify({
                iExpireTime: Math.floor(new Date().getTime() / 1000) + iLifeTime,
                mContent: mData
            }));
        } catch (e) {
            return false;
        }

        return true;
    },

    /**
     * 로컬 스토리지에서 데이터 리턴
     * @param string sKey 키
     * @return mixed 데이터
     */
    getItem: function(sKey) {
        if (this.isSupport() === false) {
            return null;
        }

        var sData = window.localStorage.getItem(sKey);
        try {
            if (sData) {
                var aData = JSON.parse(sData);
                if (aData.iExpireTime > Math.floor(new Date().getTime() / 1000)) {
                    return aData.mContent;
                } else {
                    window.localStorage.removeItem(sKey);
                }
            }
        } catch (e) { }

        return null;
    },

    /**
     * 로컬 스토리지에서 데이터 삭제
     * @param string sKey 키
     */
    removeItem: function(sKey) {
        if (this.isSupport() === false) {
            return;
        }

        window.localStorage.removeItem(sKey);
    }
};

/**
 * 좋아요 관련 공통
 */
var EC_SHOP_FRONT_NEW_LIKE_COMMON = {
    CACHE_LIFE_TIME: 3600,
    CACHE_KEY_MY_LIKE_CATEGORY: 'localMyLikeCategoryNoList',
    CACHE_KEY_MY_LIKE_PRODUCT: 'localMyLikeProductNoList',

    aConfig: {
        bIsUseLikeProduct: false,
        bIsUseLikeCategory: false
    },

    init: function(aConfig)
    {
        this.aConfig = aConfig;
    },

    /**
     * 내 분류 좋아요 번호 리스트를 가져와서 successCallbackFn 콜백 함수를 실행합니다.
     * @param function successCallbackFn 성공시 실행할 콜백 함수
     * @param function completeCallbackFn ajax 호출 완료 후 실행할 콜백 함수
     */
    getMyLikeCategoryNoInList: function(successCallbackFn, completeCallbackFn)
    {
        var self = this;

        var aData = EC_SHOP_FRONT_NEW_LIKE_BROWSER_CACHE.getItem(self.CACHE_KEY_MY_LIKE_CATEGORY);
        if (aData !== null) {
            successCallbackFn(aData);
            if (typeof completeCallbackFn === 'function') {
                completeCallbackFn();
            }
        } else {
            EC$.ajax({
                url: '/exec/front/shop/LikeCommon',
                type: 'get',
                data: {
                    'mode'   : 'getMyLikeCategoryNoInList'
                },
                dataType: 'json',
                success: function(oReturn) {
                    if (oReturn.bResult === true) {
                        aData = oReturn.aData;
                        EC_SHOP_FRONT_NEW_LIKE_BROWSER_CACHE.setItem(self.CACHE_KEY_MY_LIKE_CATEGORY, aData, self.CACHE_LIFE_TIME);
                        successCallbackFn(aData);
                    }
                },
                complete: function() {
                    completeCallbackFn();
                }
            });
        }
    },

    /**
     * 내 분류 좋아요 번호 리스트 캐시를 퍼지합니다.
     */
    purgeMyLikeCategoryNoInList: function()
    {
        EC_SHOP_FRONT_NEW_LIKE_BROWSER_CACHE.removeItem(this.CACHE_KEY_MY_LIKE_CATEGORY);
    },

    /**
     * 내 상품 좋아요 번호 리스트를 가져와서 successCallbackFn 콜백 함수를 실행합니다.
     * @param function successCallbackFn 성공시 실행할 콜백 함수
     * @param function completeCallbackFn ajax 호출 완료 후 실행할 콜백 함수
     */
    getMyLikeProductNoInList: function(successCallbackFn, completeCallbackFn)
    {
        var self = this;

        var aData = EC_SHOP_FRONT_NEW_LIKE_BROWSER_CACHE.getItem(self.CACHE_KEY_MY_LIKE_PRODUCT);
        if (aData !== null) {
            successCallbackFn(aData);
            if (typeof completeCallbackFn === 'function') {
                completeCallbackFn();
            }
        } else {
            EC$.ajax({
                url: '/exec/front/shop/LikeCommon',
                type: 'get',
                data: {
                    'mode'   : 'getMyLikeProductNoInList'
                },
                dataType: 'json',
                success: function(oReturn) {
                    if (oReturn.bResult === true) {
                        aData = oReturn.aData;
                        EC_SHOP_FRONT_NEW_LIKE_BROWSER_CACHE.setItem(self.CACHE_KEY_MY_LIKE_PRODUCT, aData, self.CACHE_LIFE_TIME);
                        successCallbackFn(aData);
                    }
                },
                complete: function() {
                    completeCallbackFn();
                }
            });
        }
    },

    /**
     * 내 상품 좋아요 번호 리스트 캐시를 퍼지합니다.
     */
    purgeMyLikeProductNoInList: function()
    {
        EC_SHOP_FRONT_NEW_LIKE_BROWSER_CACHE.removeItem(this.CACHE_KEY_MY_LIKE_PRODUCT);
    },
    // 숫자 관련 콤마 제거 처리(ECHOSTING-260504)
    getNumericRemoveCommas : function(mText) {
        var sSearchCommas = ',';
        var sReplaceEmpty = '';

        if (EC$.inArray(typeof(mText), ['number', 'undefined']) > -1) {
            return mText;
        }

        while (mText.indexOf(sSearchCommas) > -1) {
            mText = mText.replace(sSearchCommas, sReplaceEmpty);
        }

        return mText;
    },
    // 숫자 관련 콤마 처리 (ECHOSTING-260504)
    getNumberFormat : function(iNumber)
    {
        iNumber += '';

        var objRegExp = new RegExp('(-?[0-9]+)([0-9]{3})');
        while (objRegExp.test(iNumber)) {
            iNumber = iNumber.replace(objRegExp, '$1,$2');
        }

        return iNumber;
    }
};

/**
 * 목록 > 상품 좋아요.
 */
var EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT = {
    bIsReady    : false,   // 좋아요 클릭준비완료 여부.
    bIsSetEvent : false,   // 좋아요 버튼 이벤트 지정 여부.
    aImgSrc     : [], // 좋아요(On/Off) 아이콘 경로.
    aImgAlt     : [], // 좋아요(On/Off) 아이콘 Alt태그
    aMyLikePrdNo: [], // 유저가 이미 좋아요 선택한 상품번호
    oMyshopLikeCntNode : null, // layout_shopingInfo 좋아요 span 노드

    // 상품 좋아요 초기화
    init : function() {
        // 상품 좋아요 사용안함시
        if (EC_SHOP_FRONT_NEW_LIKE_COMMON.aConfig.bIsUseLikeProduct !== true) {
            return;
        }

        // ajax 유저가 이미 좋아요 선택한 상품번호 얻기 + 아이콘세팅
        this.setLoadData();
    },

    // 유저가 이미 좋아요 선택한 상품번호 얻기 + 아이콘세팅
    setLoadData : function() {
        if (EC$('.likePrdIcon').count < 1) {
            return;
        }

        var self = this;

        EC_SHOP_FRONT_NEW_LIKE_COMMON.getMyLikeProductNoInList(function(aData) {
            self.aImgSrc = aData.imgSrc;
            self.aImgAlt = aData.imgAlt;
            self.aMyLikePrdNo = aData.rows;

            // 아이콘(on) 세팅
            self.setMyLikeProductIconOn();

            // 좋아요 클릭 이벤트핸들러 지정
            if (self.bIsSetEvent === false) {
                self.setEventHandler();
                self.bIsSetEvent = true;
            }
        }, function() {
            self.bIsReady = true;
        });
    },

    // 페이지 로드시 유저가 좋아요한 상품 On.아이콘으로 변경
    setMyLikeProductIconOn : function() {
        var aData = this.aMyLikePrdNo;

        for (var i=0; i < aData.length; i++) {
            // selected 스타일 적용
            EC$(".likePrd_" + aData[i].product_no).each(function() {
                EC$(this).addClass('selected');
            });

            // 아이콘 이미지경로 변경
            EC$(".likePrdIcon[product_no='" + aData[i].product_no + "']").each(function() {
                EC$(this).attr({'src':EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.aImgSrc.on, 'icon_status':'on', 'alt' : EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.aImgAlt.on});
            });
        }
    },

    // 이벤트핸들러 지정
    setEventHandler : function() {
        // 좋아요 아이콘 클릭 이벤트
        try {
            EC$(document).on('click', '.likePrd', EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.clickLikeIcon);
        } catch (e) {}

        var sContext = '';
        if (typeof(PREVIEWPRDOUCT) === 'undefined') {
            sContext = window.parent.document;
        }
        // 마이쇼핑 > 상품좋아요 페이지
        if (EC$(".xans-myshop-likeproductlist", sContext).length > 0) {
            // 팝업 확대보기창 닫기 이벤트
            if (EC$(".xans-product-zoompackage").length > 0) {
                EC$('.xans-product-zoompackage div.close').on('click', EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.closeZoomReload);
            }
        }
    },

    // 좋아요 아이콘 클릭 이벤트핸들러
    clickLikeIcon : function() {
        if (EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.bIsReady === false ) {
            return;
        }

        // 클릭한 상품의 좋아요수, 아이콘 정보얻기
        var iPrdNo     = EC$('.likePrdIcon', this).attr('product_no');
        var iCateNo    = EC$('.likePrdIcon', this).attr('category_no');
        var sIconStatus= EC$('.likePrdIcon', this).attr('icon_status');
        // 카운트 string > int 형으로 변환 (ECHOSTING-260504)
        var iLikeCount = EC_SHOP_FRONT_NEW_LIKE_COMMON.getNumericRemoveCommas(EC$('.likePrdCount', this).text());

        // 아이콘경로 및 좋아요수 증감처리
        var sNewImgSrc = sNewIconStatus = "";
        var iNewLikeCount = 0;
        var oLikeWrapNode = EC$(".likePrd_" + iPrdNo);

        if (sIconStatus === 'on') {
            sNewIconStatus = 'off';
            sNewImgSrc = EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.aImgSrc.off;
            sNewImgAlt = EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.aImgAlt.off;
            if (iLikeCount > 0) {
                iNewLikeCount = --iLikeCount;
            }

            oLikeWrapNode.each(function() {
                EC$(this).removeClass('selected');
            });
        } else {
            sNewIconStatus = 'on';
            sNewImgSrc = EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.aImgSrc.on;
            sNewImgAlt = EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.aImgAlt.on;
            iNewLikeCount = ++iLikeCount;

            // 동일상품 selected 스타일적용
            oLikeWrapNode.each(function() {
                EC$(this).addClass('selected');
            });
        }
        // 좋아요 카운트 number_format (ECHOSTING-260504)
        iNewLikeCount = EC_SHOP_FRONT_NEW_LIKE_COMMON.getNumberFormat(iNewLikeCount);
        // 상단.layout_shopingInfo 좋아요수 업데이트
        EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.updateShopInfoCount(sNewIconStatus);

        // 좋아요 아이콘이미지 + 좋아요수 업데이트
        EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.updateLikeIconCount(iPrdNo, sNewImgSrc, sNewIconStatus, iNewLikeCount, sNewImgAlt);

        // ajax 호출 좋아요수(상품) + 마이쇼핑 좋아요 저장
        EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.submitMyLikeProduct(iPrdNo, iCateNo, sNewIconStatus);

        // 확대보기 팝업에서 좋아요 클릭시, 부모프레임 좋아요 업데이트
        if (EC$(".xans-product-zoompackage").length > 0) {
            window.parent.EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.updateShopInfoCount(sNewIconStatus);
            window.parent.EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.updateLikeIconCount(iPrdNo, sNewImgSrc, sNewIconStatus, iNewLikeCount);
        }
    },

    // 마이쇼핑 > 상품좋아요 목록 > 팝업 확대보기창 닫기 이벤트핸들러
    closeZoomReload : function() {
        var sIconsStatus = EC$('.xans-product-zoompackage .likePrdIcon').attr('icon_status');

        // 팝업에서 좋아요를 취소했으면 좋아요 목록 새로고침
        if (sIconsStatus === 'off') {
            window.parent.location.reload();
        }
    },

    // 좋아요 아이콘이미지 + 좋아요수 업데이트
    updateLikeIconCount : function(iPrdNo, sImgSrc, sIconStatus, iLikeCount, sNewImgAlt) {
        // 클릭한 동일상품 아이콘 변경
        EC$(".likePrdIcon[product_no='" + iPrdNo + "']").each(function() {
            EC$(this).attr({'src':sImgSrc, 'icon_status':sIconStatus, 'alt' : sNewImgAlt});
        });

        // 클릭한 동일상품 좋아요수 변경
        EC$('.likePrdCount_' + iPrdNo).each(function() {
            EC$(this).text(iLikeCount);
        });
    },

    // 상단.layout_shopingInfo 좋아요수 업데이트
    updateShopInfoCount : function(sIconStatus) {
        if (EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.oMyshopLikeCntNode === null) {
            EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.oMyshopLikeCntNode = EC$('#xans_myshop_like_prd_cnt');
        }

        var iMyshopLikeCnt;
        if (EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.oMyshopLikeCntNode !== null) {
            iMyshopLikeCnt = parseInt(EC$(EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.oMyshopLikeCntNode).text() );
            iMyshopLikeCnt = (sIconStatus === 'on') ? iMyshopLikeCnt + 1  : iMyshopLikeCnt - 1;
            iMyshopLikeCnt = (iMyshopLikeCnt < 0 || isNaN(iMyshopLikeCnt)) ? 0 : iMyshopLikeCnt;
            EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.oMyshopLikeCntNode.text(iMyshopLikeCnt + '개');
        }

        if (EC$('#xans_myshop_main_like_prd_cnt').length > 0 && iMyshopLikeCnt >= 0) {
            EC$('#xans_myshop_main_like_prd_cnt').text(iMyshopLikeCnt);
        }
    },

    // 상품 좋아요수 + 마이쇼핑 좋아요 저장
    submitMyLikeProduct : function(iPrdNo, iCateNo, sIconStatus) {
        if (sIconStatus === 'on') {
            this.aMyLikePrdNo.push(iPrdNo);
        } else {
            this.aMyLikePrdNo.pop(iPrdNo);
        }

        EC$.ajax({
            url: '/exec/front/shop/LikeCommon',
            type: 'get',
            data: {
                'mode'    : 'saveMyLikeProduct',
                'iPrdNo'  : iPrdNo,
                'iCateNo' : iCateNo,
                'sIconStatus': sIconStatus
            },
            dataType: 'json',
            success: function(oReturn) {
                if (oReturn.bResult === true) {
                    EC_SHOP_FRONT_NEW_LIKE_COMMON.purgeMyLikeProductNoInList();
                }
            },
            complete: function() {
                EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.bIsReady = true;
            }
        });
    }
};

EC$(function() {
    EC_SHOP_FRONT_NEW_LIKE_COMMON_PRODUCT.init();  // 상품 좋아요.
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

/**
 * 비동기식 데이터 - 관심상품 갯수
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Wishcount');
CAPP_ASYNC_METHODS.Wishcount = {
    __iWishCount: null,

    __$target: EC$('#xans_myshop_interest_prd_cnt'),
    __$target2: CAPP_ASYNC_METHODS.$xansMyshopMain.find('.xans_myshop_main_interest_prd_cnt'),

    isUse: function()
    {
        if (this.__$target.length > 0) {
            return true;
        }
        if (this.__$target2.length > 0) {
            return true;
        }

        if ( typeof EC_APPSCRIPT_SDK_DATA != "undefined" && EC$.inArray('personal', EC_APPSCRIPT_SDK_DATA ) > -1 ) {
            return true;
        }

        return false;
    },

    restoreCache: function()
    {
        var sCookieName = 'wishcount_' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            this.__iWishCount = parseInt(aCookieValue[1], 10);
            return true;
        }

        return false;
    },

    setData: function(sData)
    {
        this.__iWishCount = Number(sData);
    },

    execute: function()
    {
        if (SHOP.getLanguage() === 'ko_KR') {
            this.__$target.html(this.__iWishCount + '개');
        } else {
            this.__$target.html(this.__iWishCount);
        }

        this.__$target2.html(this.__iWishCount);
    },

    getData: function()
    {
        return {
            count: this.__iWishCount
        };
    }
};
/**
 * 비동기식 데이터 - 최근 본 상품
 */
CAPP_ASYNC_METHODS.aDatasetList.push('recent');
CAPP_ASYNC_METHODS.recent = {
    STORAGE_KEY: 'localRecentProduct' +  EC_SDE_SHOP_NUM,

    __$target: EC$('.xans-layout-productrecent'),

    __aData: null,

    isUse: function()
    {
        this.__$target.hide();

        if (this.__$target.find('.xans-record-').length > 0) {
            return true;
        }

        return false;
    },

    restoreCache: function()
    {
        this.__aData = [];

        var iTotalCount = CAPP_ASYNC_METHODS.RecentTotalCount.getData();
        if (iTotalCount == 0) {
            // 총 갯수가 없는 경우 복구할 것이 없으므로 복구한 것으로 리턴
            return true;
        }

        var sAdultImage = '';

        if (window.sessionStorage === undefined) {
            return false;
        }

        var sSessionStorageData = window.sessionStorage.getItem(this.STORAGE_KEY);
        if (sSessionStorageData === null) {
            return false;
        }

        var iViewCount = EC_FRONT_JS_CONFIG_SHOP.recent_count;

        this.__aData = [];
        var aStorageData = EC_UTIL.parseJSON(sSessionStorageData);
        var iCount = 1;
        var bDispRecent = true;
        for (var iKey in aStorageData) {
            var sProductImgSrc = aStorageData[iKey].sImgSrc;

            if (isFinite(iKey) === false) {
                continue;
            }

            var aDataTmp = [];
            aDataTmp.recent_img = getImageUrl(sProductImgSrc);
            aDataTmp.name = aStorageData[iKey].sProductName;
            aDataTmp.disp_recent = true;
            aDataTmp.is_adult_product = aStorageData[iKey].isAdultProduct;
            aDataTmp.link_product_detail = aStorageData[iKey].link_product_detail;

            //aDataTmp.param = '?product_no=' + aStorageData[iKey].iProductNo + '&cate_no=' + aStorageData[iKey].iCateNum + '&display_group=' + aStorageData[iKey].iDisplayGroup;
            aDataTmp.param = filterXssUrlParameter(aStorageData[iKey].sParam);
            if ( iViewCount < iCount ) {
                bDispRecent = false;
            }
            aDataTmp.disp_recent = bDispRecent;

            iCount++;
            this.__aData.push(aDataTmp);
        }

        return true;

        /**
         * get SessionStorage image url
         * @param sNewImgUrl DB에 저장되어 있는 tiny값
         */
        function getImageUrl(sImgUrl)
        {
            if (typeof(sImgUrl) === 'undefined' || sImgUrl === null) {
                return;
            }
            var sNewImgUrl = '';

            if (sImgUrl.indexOf('http://') >= 0 || sImgUrl.indexOf('https://') >= 0 || sImgUrl.substr(0, 2) === '//') {
                sNewImgUrl = sImgUrl;
            } else {
                sNewImgUrl = EC_FRONT_JS_CONFIG_SHOP.cdnUrl + '/web/product/tiny/' + sImgUrl;
            }

            return sNewImgUrl;
        }

        /**
         * 파라미터 URL에서 XSS 공격 관련 파라미터를 필터링합니다. ECHOSTING-162977
         * @param string sParam 파라미터
         * @return string 필터링된 파라미터
         */
        function filterXssUrlParameter(sParam)
        {
            sParam = sParam || '';

            var sPrefix = '';
            if (sParam.substr(0, 1) === '?') {
                sPrefix = '?';
                sParam = sParam.substr(1);
            }

            var aParam = {};

            var aParamList = (sParam).split('&');
            EC$.each(aParamList, function() {
                var aMatch = this.match(/^([^=]+)=(.*)$/);
                if (aMatch) {
                    aParam[aMatch[1]] = aMatch[2];
                }
            });

            return sPrefix + EC$.param(aParam);
        }

    },

    setData: function(aData)
    {
        this.__aData = aData;

        // 쿠키엔 있지만 sessionStorage에 없는 데이터 복구
        if (window.sessionStorage) {

            var oNewStorageData = [];

            for ( var i = 0; i < aData.length; i++) {
                if (aData[i].bNewProduct !== true) {
                    continue;
                }

                var aNewStorageData = {
                    'iProductNo': aData[i].product_no,
                    'sProductName': aData[i].name,
                    'sImgSrc': aData[i].recent_img,
                    'sParam': aData[i].param,
                    'link_product_detail': aData[i].link_product_detail
                };

                oNewStorageData.push(aNewStorageData);
            }

            if ( oNewStorageData.length > 0 ) {
                sessionStorage.setItem(this.STORAGE_KEY , JSON.stringify(oNewStorageData));
            }
        }
    },

    execute: function()
    {
        var sAdult19Warning = EC_FRONT_JS_CONFIG_SHOP.adult19Warning;

        var aData = this.__aData;

        var aNodes = this.__$target.find('.xans-record-');
        var iRecordCnt = aNodes.length;
        var iAddedElementCount = 0;

        var aNodesParent = EC$(aNodes[0]).parent();
        for ( var i = 0; i < aData.length; i++) {
            if (!aNodes[i]) {
                EC$(aNodes[iRecordCnt - 1]).clone().appendTo(aNodesParent);
                iAddedElementCount++;
            }
        }

        if (iAddedElementCount > 0) {
            aNodes = this.__$target.find('.xans-record-');
        }

        if (aData.length > 0) {
            this.__$target.show();
        }

        for ( var i = 0; i < aData.length; i++) {
            assignVariables(aNodes[i], aData[i]);
        }

        // 종료 카운트 지정
        if (aData.length < aNodes.length) {
            iLength = aData.length;
            deleteNode();
        }

        recentBntInit(this.__$target);

        /**
         * 패치되지 않은 노드를 제거
         */
        function deleteNode()
        {
            for ( var i = iLength; i < aNodes.length; i++) {
                EC$(aNodes[i]).remove();
            }
        }

        /**
         * oTarget 엘레먼트에 aData의 변수를 어싸인합니다.
         * @param Element oTarget 변수를 어싸인할 엘레먼트
         * @param array aData 변수 데이터
         */
        function assignVariables(oTarget, aData)
        {
            var recentImage = aData.recent_img;

            if (sAdult19Warning === 'T' && CAPP_ASYNC_METHODS.member.getMemberIsAdult() === 'F' && aData.is_adult_product === 'T') {
                    recentImage = EC_FRONT_JS_CONFIG_SHOP.adult19BaseTinyImage;
            }

            var $oTarget = EC$(oTarget);

            var sHtml = $oTarget.html();

            sHtml = sHtml.replace('about:blank', recentImage)
                         .replace('##param##', aData.param)
                         .replace('##name##',aData.name)
                         .replace('##link_product_detail##', aData.link_product_detail);
            $oTarget.html(sHtml);

            if (aData.disp_recent === true) {
                $oTarget.removeClass('displaynone');
            }
        }

        function recentBntInit($target)
        {
            // 화면에 뿌려진 갯수
            var iDisplayCount = 0;
            // 보여지는 style
            var sDisplay = '';
            var iIdx = 0;
            //
            var iDisplayNoneIdx = 0;

            var nodes = $target.find('.xans-record-').each(function()
            {
                sDisplay = EC$(this).css('display');
                if (sDisplay != 'none') {
                    iDisplayCount++;
                } else {
                    if (iDisplayNoneIdx == 0) {
                        iDisplayNoneIdx = iIdx;
                    }

                }
                iIdx++;
            });

            var iRecentCount = nodes.length;
            var bBtnActive = iDisplayCount > 0;
            EC$('.xans-layout-productrecent .prev').off('click').click(function()
            {
                if (bBtnActive !== true) return;
                var iFirstNode = iDisplayNoneIdx - iDisplayCount;
                if (iFirstNode == 0 || iDisplayCount == iRecentCount) {
                    alert(__('최근 본 첫번째 상품입니다.'));
                    return;
                } else {
                    iDisplayNoneIdx--;
                    EC$(nodes[iDisplayNoneIdx]).hide();
                    EC$(nodes[iFirstNode - 1]).removeClass('displaynone');
                    EC$(nodes[iFirstNode - 1]).fadeIn('fast');

                }
            }).css(
            {
                cursor : 'pointer'
            });

            EC$('.xans-layout-productrecent .next').off('click').click(function()
            {
                if (bBtnActive !== true) return;
                if ( (iRecentCount ) == iDisplayNoneIdx || iDisplayCount == iRecentCount) {
                    alert(__('최근 본 마지막 상품입니다.'));
                } else {
                    EC$(nodes[iDisplayNoneIdx]).fadeIn('fast');
                    EC$(nodes[iDisplayNoneIdx]).removeClass('displaynone');
                    EC$(nodes[ (iDisplayNoneIdx - iDisplayCount)]).hide();
                    iDisplayNoneIdx++;
                }
            }).css(
            {
                cursor : 'pointer'
            });

        }

    }
};

/**
 * 비동기식 데이터 - 최근본상품 총 갯수
 */
CAPP_ASYNC_METHODS.aDatasetList.push('RecentTotalCount');
CAPP_ASYNC_METHODS.RecentTotalCount = {
    __iRecentCount: null,

    __$target: CAPP_ASYNC_METHODS.$xansMyshopMain.find('.xans_myshop_main_recent_cnt'),

    isUse: function()
    {
        if (this.__$target.length > 0) {
            return true;
        }

        return false;
    },

    restoreCache: function()
    {
        var sCookieName = 'recent_plist';
        if (EC_SDE_SHOP_NUM > 1) {
            sCookieName = 'recent_plist' + EC_SDE_SHOP_NUM;
        }
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            this.__iRecentCount = decodeURI(aCookieValue[1]).split('|').length;
        } else {
            this.__iRecentCount = 0;
        }
    },

    execute: function()
    {
        this.__$target.html(this.__iRecentCount);
    },

    getData: function()
    {
        if (this.__iRecentCount === null) {
            // this.isUse값이 false라서 복구되지 않았는데 이 값이 필요한 경우 복구
            this.restoreCache();
        }

        return this.__iRecentCount;
    }
};
/**
 * 비동기식 데이터 - 주문정보
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Order');
CAPP_ASYNC_METHODS.Order = {
    __iOrderCount: null,
    __iOrderTotalPrice: null,
    __iGradeIncreaseValue: null,

    __$target: EC$('#xans_myshop_bankbook_order_count'),
    __$target2: EC$('#xans_myshop_bankbook_order_price'),
    __$target3: EC$('#xans_myshop_bankbook_grade_increase_value'),

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
        }
        
        return false;        
    },

    restoreCache: function()
    {
        var sCookieName = 'order_' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            var aData = EC_UTIL.parseJSON(decodeURIComponent(aCookieValue[1]));
            this.__iOrderCount = aData.total_order_count;
            this.__iOrderTotalPrice = aData.total_order_price;
            this.__iGradeIncreaseValue = Number(aData.grade_increase_value);
            return true;
        }

        return false;
    },

    setData: function(aData)
    {
        this.__iOrderCount = aData['total_order_count'];
        this.__iOrderTotalPrice = aData['total_order_price'];
        this.__iGradeIncreaseValue = Number(aData['grade_increase_value']);
    },

    execute: function()
    {
        this.__$target.html(this.__iOrderCount);
        this.__$target2.html(this.__iOrderTotalPrice);
        this.__$target3.html(this.__iGradeIncreaseValue);
    },

    getData: function()
    {
        return {
            total_order_count: this.__iOrderCount,
            total_order_price: this.__iOrderTotalPrice,
            grade_increase_value: this.__iGradeIncreaseValue
        };
    }
};
/**
 * 비동기식 데이터 - Benefit
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Benefit');
CAPP_ASYNC_METHODS.Benefit = {
    __aBenefit: null,
    __$target: EC$('.xans-myshop-asyncbenefit'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (this.__$target.length > 0) {
                return true;
            }
        }

        return false;
    },

    setData: function(aData)
    {
        this.__aBenefit = aData;
    },

    execute: function()
    {
        var aFilter = ['group_image_tag', 'group_icon_tag', 'display_no_benefit', 'display_with_all', 'display_mobile_use_dc', 'display_mobile_use_mileage'];
        var __aData = this.__aBenefit;
        
        // 그룹이미지
        EC$('.myshop_benefit_group_image_tag').attr({alt: __aData['group_name'], src: __aData['group_image']});

        // 그룹아이콘
        EC$('.myshop_benefit_group_icon_tag').attr({alt: __aData['group_name'], src: __aData['group_icon']});

        if (__aData['display_no_benefit'] === true) {
            EC$('.myshop_benefit_display_no_benefit').removeClass('displaynone').show();
        }
        
        if (__aData['display_with_all'] === true) {
            EC$('.myshop_benefit_display_with_all').removeClass('displaynone').show();
        }
        
        if (__aData['display_mobile_use_dc'] === true) {
            EC$('.myshop_benefit_display_mobile_use_dc').removeClass('displaynone').show();
        } 
        
        if (__aData['display_mobile_use_mileage'] === true) {
            EC$('.myshop_benefit_display_mobile_use_mileage').removeClass('displaynone').show();
        }

        EC$.each(__aData, function(key, val) {
            if (EC$.inArray(key, aFilter) === -1) {
                EC$('.myshop_benefit_' + key).html(val);
            }
        });
    }    
};
/**
 * 비동기식 데이터 - 비동기장바구니 레이어
 */
CAPP_ASYNC_METHODS.aDatasetList.push('BasketLayer');
CAPP_ASYNC_METHODS.BasketLayer = {
    __sBasketLayerHtml: null,
    __$target: document.getElementById('ec_async_basket_layer_container'),

    isUse: function()
    {
        if (this.__$target !== null) {
            return true;
        }
        return false;
    },

    execute: function()
    {
        EC$.ajax({
            url: '/order/async_basket_layer.html?__popupPage=T',
            async: false,
            success: function(data) {
                var sBasketLayerHtml = data;
                var sBasketLayerStyle = '';
                var sBasketLayerBody = '';

                sBasketLayerHtml = sBasketLayerHtml.replace(/<script([\s\S]*?)<\/script>/gi,''); // 스크립트 제거
                sBasketLayerHtml = sBasketLayerHtml.replace(/<link([\s\S]*?)\/>/gi,''); // 옵티마이져 제거

                var regexStyle = /<style([\s\S]*?)<\/style>/; // Style 추출
                if (regexStyle.exec(sBasketLayerHtml) != null) sBasketLayerStyle = regexStyle.exec(sBasketLayerHtml)[0];

                var regexBody = /<body[\s\S]*?>([\s\S]*?)<\/body>/; // Body 추출
                if (regexBody.exec(sBasketLayerHtml) != null) sBasketLayerBody = regexBody.exec(sBasketLayerHtml)[1];

                CAPP_ASYNC_METHODS.BasketLayer.__sBasketLayerHtml = sBasketLayerStyle + sBasketLayerBody;
            }
        });
        this.__$target.innerHTML = this.__sBasketLayerHtml;
    }
};
/**
 * 비동기식 데이터 - Benefit
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Grade');
CAPP_ASYNC_METHODS.Grade = {
    __aGrade: null,
    __$target: EC$('#sGradeAutoDisplayArea'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (this.__$target.length > 0) {
                return true;
            }
        }

        return false;
    },

    setData: function(aData)
    {
        this.__aGrade = aData;
    },

    execute: function()
    {
        var __aData = this.__aGrade;
        var aFilter = ['bChangeMaxTypePrice', 'bChangeMaxTypePriceAndCount', 'bChangeMaxTypePriceOrCount', 'bChangeMaxTypeCount'];

        var aMaxDisplayJson = {
            "bChangeMaxTypePrice": [
                {"sId": "sChangeMaxTypePriceArea"}
            ],
            "bChangeMaxTypePriceAndCount": [
                {"sId": "sChangeMaxTypePriceAndCountArea"}
            ],
            "bChangeMaxTypePriceOrCount": [
                {"sId": "sChangeMaxTypePriceOrCountArea"}
            ],
            "bChangeMaxTypeCount": [
                {"sId": "sChangeMaxTypeCountArea"}
            ]
        };

        if (EC$('.sNextGroupIconArea').length > 0) {
            if (__aData['bDisplayNextGroupIcon'] === true) {
                EC$('.sNextGroupIconArea').removeClass('displaynone').show();
                EC$('.myshop_benefit_next_group_icon_tag').attr({alt: __aData['sNextGrade'], src: __aData['sNextGroupIcon']});
            } else {
                EC$('.sNextGroupIconArea').addClass('displaynone');
            }
        }

        var sIsAutoGradeDisplay = "F";
        EC$.each(__aData, function(key, val) {
            if (EC$.inArray(key, aFilter) === -1) {
                return true;
            }
            if (val === true) {
                if (EC$('#'+aMaxDisplayJson[key][0].sId).length > 0) {
                    EC$('#' + aMaxDisplayJson[key][0].sId).removeClass('displaynone').show();
                }
                sIsAutoGradeDisplay = "T";
            }
        });
        if (sIsAutoGradeDisplay == "T" && EC$('#sGradeAutoDisplayArea .sAutoGradeDisplay').length > 0) {
            EC$('#sGradeAutoDisplayArea .sAutoGradeDisplay').addClass('displaynone');
        }

        EC$.each(__aData, function(key, val) {
            if (EC$.inArray(key, aFilter) === -1) {
                if (EC$('.xans-member-var-' + key).length > 0) {
                    EC$('.xans-member-var-' + key).html(val);
                }
            }
        });
    }    
};
/**
 * 비동기식 데이터 - Benefit
 */
CAPP_ASYNC_METHODS.aDatasetList.push('AutomaticGradeShow');
CAPP_ASYNC_METHODS.AutomaticGradeShow = {
    __aGrade: null,
    __$target: EC$('#sAutomaticGradeShowArea'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (this.__$target.length > 0) {
                return true;
            }
        }
        return false;
    },

    setData: function(aData)
    {
        this.__aGrade = aData;
    },

    execute: function()
    {
        var __aData = this.__aGrade;

        /**
         * 아이콘 표기 제외
        if (EC$('.sNextGroupIconArea').length > 0) {
            if (__aData['bDisplayNextGroupIcon'] === true) {
                EC$('.sNextGroupIconArea').removeClass('displaynone').show();
                EC$('.myshop_benefit_next_group_icon_tag').attr({alt: __aData['sNextGrade'], src: __aData['sNextGroupIcon']});
            } else {
                EC$('.sNextGroupIconArea').addClass('displaynone');
            }
        }
         */

        var sIsAutoGradeDisplay = "F";
        EC$.each(__aData, function(key, val) {
            if (val === true) {
                sIsAutoGradeDisplay = "T";
                return false;
            }
        });
        if (sIsAutoGradeDisplay == "T" && EC$('#sAutomaticGradeShowArea .sAutoGradeDisplay').length > 0) {
            EC$('#sAutomaticGradeShowArea .sAutoGradeDisplay').addClass('displaynone');
        }

        EC$.each(__aData, function(key, val) {
            if (EC$('.xans-member-var-' + key).length > 0) {
                EC$('.xans-member-var-' + key).html(val);
            }
        });
    }    
};
/**
 * 비동기식 데이터 - 비동기장바구니 레이어
 */
CAPP_ASYNC_METHODS.aDatasetList.push('MobileMutiPopup');
CAPP_ASYNC_METHODS.MobileMutiPopup = {
    __$target: EC$('div[class^="ec-async-multi-popup-layer-container"]'),

    isUse: function()
    {
        if (this.__$target.length > 0) {
            return true;
        }
        return false;
    },

    execute: function()
    {
        for (var i=0; i < this.__$target.length; i++) {
            EC$.ajax({
                url: '/exec/front/popup/AjaxMultiPopup?index='+i,
                data : EC_ASYNC_MULTI_POPUP_OPTION[i],
                dataType: "json",
                success : function (oResult) {
                    switch (oResult.code) {
                        case '0000' :
                            if (oResult.data.length < 1) {
                                break;
                            }
                            EC$('.ec-async-multi-popup-layer-container-' + oResult.data.html_index).html(oResult.data.html_text);
                            if (oResult.data.type == 'P') {
                                BANNER_POPUP_OPEN.setPopupSetting();
                                BANNER_POPUP_OPEN.setPopupWidth();
                                BANNER_POPUP_OPEN.setPopupClose();
                            } else {
                                /**
                                 * 이중 스크롤 방지 클래스 추가(비동기) 
                                 *
                                 */
                                EC$('body').addClass('eMobilePopup');
                                EC$('body').width('100%');

                                BANNER_POPUP_OPEN.setFullPopupSetting();
                                BANNER_POPUP_OPEN.setFullPopupClose();
                            }
                            break;
                        default :
                            break;
                    }
                },
                error : function () {
                }
            });
        }
    }
};
/**
 * 비동기식 데이터 - 좋아요 상품 갯수
 */
CAPP_ASYNC_METHODS.aDatasetList.push('MyLikeProductCount');
CAPP_ASYNC_METHODS.MyLikeProductCount = {
    __iMyLikeCount: null,

    __$target: EC$('#xans_myshop_like_prd_cnt'),
    __$target_main: EC$('#xans_myshop_main_like_prd_cnt'),
    isUse: function()
    {
        if (this.__$target.length > 0 && SHOP.getLanguage() === 'ko_KR') {
            return true;
        }

        if (this.__$target_main.length > 0 && SHOP.getLanguage() === 'ko_KR') {
            return true;
        }

        return false;
    },
    restoreCache: function()
    {
        var sCookieName = 'like_product_cnt' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            this.__iMyLikeCount = parseInt(aCookieValue[1], 10);
            return true;
        }

        return false;
    },

    setData: function(sData)
    {
        this.__iMyLikeCount = Number(sData);
    },

    execute: function()
    {
        if (SHOP.getLanguage() === 'ko_KR') {
            this.__$target.html(this.__iMyLikeCount + '개');
            this.__$target_main.html(this.__iMyLikeCount);
        }
    }
};
/**
 * 비동기식 데이터 - 좋아요 상품 list
 */
CAPP_ASYNC_METHODS.aDatasetList.push('MyLikeProductList');
CAPP_ASYNC_METHODS.MyLikeProductList = {
    __aMyLikeList: null,
    __iMyLikeListLimit : 10,
    __$target: EC$('.xans-product-likeproductasync'),
    isUse: function()
    {
        if (this.__$target.length > 0 && SHOP.getLanguage() === 'ko_KR') {
            return true;
        }

        if (EC$('#EC_LIKE_ASYNC_LINK_DATA_LIST').length > 0) {
            return true;
        }
        return false;
    },
    setData: function(aData)
    {
        this.__iMyLikeListLimit = EC_FRONT_JS_CONFIG_SHOP.aSyncLikeLimit;
        this.__aMyLikeList = aData;
    },
    execute: function()
    {

        if (this.__aMyLikeList === null || this.__aMyLikeList.length === 0) {
            EC$('#EC_LIKE_ASYNC_LINK_DATA_EMPTY').html('');
            return;
        }

        //EC$('#EC_LIKE_ASYNC_LINK_DATA_EMPTY').remove();
        var sSpaceIcon = ' ';
        for (var iKey = 0; iKey < this.__aMyLikeList.length ; iKey++) {
            var oRowData = EC$('#EC_LIKE_ASYNC_LINK_DATA_LIST_TEMP').clone().removeAttr('id');
            oRowData.find('a[href^="/product/detail.html"').attr('href', this.__aMyLikeList[iKey].link_product_detail);
            oRowData.find('.thumb img').attr('src',this.__aMyLikeList[iKey].image_medium);
            oRowData.find('.EC_LIKE_ASYNC_LINK_DATA_PRODUCT_NAME').html('<a href="' + this.__aMyLikeList[iKey].link_product_detail + '">' + this.__aMyLikeList[iKey].disp_product_name + '</a>');

            var sIconListHtml = this.__aMyLikeList[iKey].soldout_icon + sSpaceIcon +  this.__aMyLikeList[iKey].stock_icon + sSpaceIcon + this.__aMyLikeList[iKey].recommend_icon + sSpaceIcon +
                this.__aMyLikeList[iKey].new_icon + sSpaceIcon + this.__aMyLikeList[iKey].product_icons + sSpaceIcon + this.__aMyLikeList[iKey].benefit_icons;
             if (sIconListHtml !== '') {
                oRowData.find('.EC_LIKE_ASYNC_LINK_DATA_ICON_LIST').html(sIconListHtml);
            }

            EC$('#EC_LIKE_ASYNC_LINK_DATA_APPEND').append(oRowData);

            if (iKey >= (this.__iMyLikeListLimit - 1)) {
                break;
            }
        }
        EC$('#EC_LIKE_ASYNC_LINK_DATA_LIST_TEMP').remove();
        if (this.__aMyLikeList.length < this.__iMyLikeListLimit) {
            EC$('#EC_LIKE_ASYNC_LINK_DATA_MORE_VIEW').remove();
        }

        if (EC_FRONT_JS_CONFIG_SHOP.bAutoView === 'T') {
            document.getElementById('EC_LIKE_ASYNC_LINK_DATA_LIST').style.display = 'block';
        }

    }
};
