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
/**
 * 라이브 링콘 on/off이미지
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Livelinkon');
CAPP_ASYNC_METHODS.Livelinkon = {
    __$target: EC$('#ec_livelinkon_campain_on'),
    __$target2: EC$('#ec_livelinkon_campain_off'),

    isUse: function()
    {
        if (this.__$target.length > 0 && this.__$target2.length > 0) {
            return true;
        }
        return false;
    },

    execute: function()
    {
        var sCampaignid = '';
        if (EC_ASYNC_LIVELINKON_ID != undefined) {
            sCampaignid = EC_ASYNC_LIVELINKON_ID;
        }
        EC$.ajax({
            url: '/exec/front/Livelinkon/Campaignajax?campaign_id='+sCampaignid,
            async: false,
            success: function(data) {
                if (data == 'on') {
                    CAPP_ASYNC_METHODS.Livelinkon.__$target.removeClass('displaynone').show();
                    CAPP_ASYNC_METHODS.Livelinkon.__$target2.removeClass('displaynone').hide();
                } else if (data == 'off') {
                    CAPP_ASYNC_METHODS.Livelinkon.__$target.removeClass('displaynone').hide();
                    CAPP_ASYNC_METHODS.Livelinkon.__$target2.removeClass('displaynone').show();
                } else {
                    CAPP_ASYNC_METHODS.Livelinkon.__$target.removeClass('displaynone').hide();
                    CAPP_ASYNC_METHODS.Livelinkon.__$target2.removeClass('displaynone').hide();
                }
            }
        });
    }
};
/**
 * 비동기식 데이터 - 마이쇼핑 > 주문 카운트 (주문 건수 / CS건수 / 예전주문)
 */
CAPP_ASYNC_METHODS.aDatasetList.push('OrderHistoryCount');
CAPP_ASYNC_METHODS.OrderHistoryCount = {
    __sTotalOrder: null,
    __sTotalOrderCs: null,
    __sTotalOrderOld: null,

    __$target: EC$('#ec_myshop_total_orders'),
    __$target2: EC$('#ec_myshop_total_orders_cs'),
    __$target3: EC$('#ec_myshop_total_orders_old'),

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

    setData: function(aData)
    {
        this.__sTotalOrder = aData['total_orders'];
        this.__sTotalOrderCs = aData['total_orders_cs'];
        this.__sTotalOrderOld = aData['total_orders_old'];

    },

    execute: function()
    {
        this.__$target.html(this.__sTotalOrder);
        this.__$target2.html(this.__sTotalOrderCs);
        this.__$target3.html(this.__sTotalOrderOld);
    }
};
/**
 * 주문조회 > 주문내역조회 및 취소/교환/반품내역 등 탭(OrderHistoryTab) 갯수 비동기호출
 */
CAPP_ASYNC_METHODS.aDatasetList.push('OrderHistoryTab');
CAPP_ASYNC_METHODS.OrderHistoryTab = {
    __$targetTotalOrders: EC$('#xans_myshop_total_orders'),
    __$targetTotalOrdersCs: EC$('#xans_myshop_total_orders_cs'),
    __$targetTotalOrdersPast: EC$('#xans_myshop_total_orders_past'),
    __$targetTotalOrdersOld: EC$('#xans_myshop_total_orders_old'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (EC$('.xans-myshop-orderhistorytab').length > 0) {
                return true;
            }
        }
        return false;
    },
    execute: function()
    {
        try {
            var mode = this.getUrlParam('mode');
            var order_id = this.getUrlParam('order_id');
            var order_status = this.getUrlParam('order_status');
            var history_start_date = this.getUrlParam('history_start_date');
            var history_end_date = this.getUrlParam('history_end_date');
            var past_year = this.getUrlParam('past_year');
            var count = this.getUrlParam('count');

            var sPathName = window.location.pathname;

            var oParameters = {
                'mode': mode == null ? '' : mode,
                'order_id': order_id == null ? '' : order_id,
                'order_status': order_status == null ? '' : order_status,
                'history_start_date': history_start_date == null ? '' : history_start_date,
                'history_end_date': history_end_date == null ? '' : history_end_date,
                'past_year': past_year == null ? '' : past_year,
                'count': count == null ? '' : count,
                'page_name': sPathName.substring(sPathName.lastIndexOf("/") + 1, sPathName.indexOf('.'))
            };

            if (typeof EC_ASYNC_ORDERHISTORYTAB_ORDER_ID !== 'undefined') {
                oParameters['encrypted_str'] = EC_ASYNC_ORDERHISTORYTAB_ORDER_ID;
            }

            var oThis = this;

            EC$.ajax({
                url: '/exec/front/Myshop/OrderHistoryTab',
                dataType: 'json',
                data: oParameters,
                success: function (aData) {
                    if (aData['result'] === true) {
                        oThis.__$targetTotalOrders.html(aData['total_orders']);
                        oThis.__$targetTotalOrdersCs.html(aData['total_orders_cs']);
                        oThis.__$targetTotalOrdersOld.html(aData['total_orders_old']);
                        oThis.__$targetTotalOrdersPast.html(aData['total_orders_past']);

                        var oTabATagList = {
                            'param' : EC$('.tab_class a'),
                            'param_cs' : EC$('.tab_class_cs a'),
                            'param_past' : EC$('.tab_class_past a'),
                            'param_old' : EC$('.tab_class_old a'),
                        };
                        var sHref;
                        EC$.each(oTabATagList, function(sKey, oTarget) {
                            if (oTarget.length > 0) {
                                sHref = oTarget.attr("href");
                                sHref = sHref.replace("$" + sKey, aData[sKey]);
                                oTarget.attr("href", sHref);
                            }
                        });

                        EC$("." + aData['selected_tab_class']).addClass('selected');

                        if (aData['is_past_list_display'] === false) {
                            EC$('.tab_class_past').addClass("displaynone");
                        } else {
                            EC$('.tab_class_past').removeClass("displaynone");
                        }

                        if (aData['old_list_display'] === false) {
                            EC$('.tab_class_old').addClass("displaynone");
                        } else {
                            EC$('.tab_class_old').removeClass("displaynone");
                        }
                    }
                }
            });
        } catch (oError) {
            this.errorAjaxCall(oError);
        }
    },
    getUrlParam : function(name)
    {
        var param = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (param == null) {
            return null;
        } else {
            return decodeURI(param[1]) || null;
        }
    },
    errorAjaxCall : function(oError)
    {
        var sError = oError.toString();
        var aMatch = sError.match(/Error*/g);

        if ( typeof(oError) !== 'object' || aMatch == null || aMatch.length < 1 || !oError.stack ) return;

        EC$.ajax({
            url: '/exec/front/order/FormJserror/',
            method: 'POST',
            cache: false,
            async : false,
            data: {
                errorMessage : oError.message,
                errorStack : oError.stack,
                errorName : oError.name
            }
        });
    }
};
var PathRoleValidator = (function() {
    /**
     * Milage, Deposit 의 경우 처리되지 말아야할 페이지 확인
     * @returns {boolean}
     */
    function isInvalidPathRole()
    {
        // path role
        var sCurrentPathRole = null;

        // // euckr 환경에서 path role 획득
        if (SHOP.getProductVer() === 1) {
            // path 와 role 매핑
            var aPathRoleMap = {
                '/myshop/index.html': 'MYSHOP_MAIN',
                '/myshop/mileage/historyList.html': 'MYSHOP_MILEAGE_LIST',
                '/myshop/deposits/historyList.html': 'MYSHOP_DEPOSIT_LIST',
                '/order/orderform.html': 'ORDER_ORDERFORM'
            };

            // 페이지 경로로부터 path role 획득
            sCurrentPathRole = aPathRoleMap[document.location.pathname];

            // utf8 환경에서 path role 획득
        } else {
            // 현재 페이지 path role 획득
            sCurrentPathRole = EC$('meta[name="path_role"]').attr('content');
        }

        // 처리되면 안되는 경로
        var aInvalidPathRole = [
            'MYSHOP_MAIN',
            'MYSHOP_MILEAGE_LIST',
            'MYSHOP_DEPOSIT_LIST',
            'ORDER_ORDERFORM'
        ];

        return EC$.inArray(sCurrentPathRole, aInvalidPathRole) >= 0;
    }

    return {
        isInvalidPathRole: isInvalidPathRole
    };
})();
EC$(function()
{
    CAPP_ASYNC_METHODS.init();
});
var EC_MANAGE_PRODUCT_RECENT = {
    getRecentImageUrl : function()
    {
        var sStorageKey = 'localRecentProduct' + EC_SDE_SHOP_NUM;

        if (typeof(sessionStorage[sStorageKey]) !== 'undefined') {
            var sRecentData = sessionStorage.getItem(sStorageKey);
            var oJsonData = JSON.parse(sRecentData);
            var sImageSrc = '';

            if (oJsonData[0] !== undefined) {
                sImageSrc = oJsonData[0].sImgSrc;
            }
            
            document.location.replace('recentproduct://setinfo?simg_src=' + sImageSrc);
        }
    }
};

var EC_MANAGE_MEMBER = {
    // 카카오싱크 로그인
    kakaosyncLogin : function (clientSecret)
    {
        if (Kakao.isInitialized()) {
            Kakao.cleanup();
        }
        Kakao.init(clientSecret);

        Kakao.Auth.authorize({
            redirectUri: location.origin + '/Api/Member/Oauth2ClientCallback/kakao/'
        });
    }
};