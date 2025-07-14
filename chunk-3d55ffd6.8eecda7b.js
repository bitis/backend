(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["chunk-3d55ffd6"], {
    "10d5": function (t, i, e) {
    }, "17b3": function (t, i, e) {
        "use strict";
        e("10d5")
    }, "38cc": function (t, i) {
        t.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAAAAXNSR0IArs4c6QAAAYBQTFRFAAAA/wAA/wAA/zMz/ysr/yQk/0Ag/zkc/y4u/zsn/zou/zUr/zMp/zkv/zUt/zky/zcw/zQu/zou/zcs/zYx/zUw/zgu/zkw/zcv/zku/zcw/zYw/zgx/zcw/zkw/zcx/zgv/zgv/zcx/zcv/zkx/zgw/zcv/zkv/zgx/zcw/zkw/zgx/zkw/zkw/zgw/zcx/zgw/zkx/zgw/zkw/zgx/zkw/zkx/zkw/zgw/zgw/zgx/zkw/zkw/zgx/zkw/zgx/zkx/zkw/zgw/zgw/zkx/zgw/zgx/zgw/zgw/zgx/zgx/zkw/zgx/zgx/zgx/zkw/zkw/zkw/zkw/zgw/zgw/zkx/zgx/zgw/zgx/zgw/zgx/zgx/zkw/zkw/zgx/zgx/zkx/zkw/zgw/zgw/zgw/zkx/0U9/1BJ/15X/2hh/2lj/3dx/4N+/5CL/6Gd/6il/7Sx/7Wy/8G+/8G//8PA/87M/9nY/+bl/+fm/+fn/+zr//Ly//Pz//z7//z8////XQli3QAAAGV0Uk5TAAECBQYHCAkLDRYYGRsiJCUnLC4vMDc6RkhKS05PUFNWV1hcXmBhZ2hqa21wdXZ4e36AkJOUmJmkpaiqsLKztri5usDBxMfIyczNz9DR0tPU2N3e3+Di4+fo8PHy8/X29/j5/f7N+xp6AAACXklEQVRYw63X61cSQRgG8EeRS9jVsqS0O6YFWSJqSF5aiqLENMFaKlJ4ycoudtGKav71Piixy+7Mzgs9nxgOv+fscvbsvANIEh5NTC/kiuVyMbcwnRgNg5XwVaNCtlQMRsflDLkmc0mLDy6RNPkznvzkPVLm7ikl9yXJMxM9cn/4AWnk/hGZH3pBWnl+zpUHkqSdiaDL5T8mRvLO25ghVlKtfoyYGbP7gQq3oBKx+hMlYsfstxTkqY1kmv4itZWzDd/9hKFqb/99fNS9XxBl+K262N1sLKJ73s+4gC0hhKjX9lcrfgBAjOmFeN9YxwAAWa5vFmQBoLfK9d8bt0DVgwBGuP7XZvO7EQDzHXiaBw5sdOBpw6f7FLp7ogjGO/I0jsmOPE0ibXvQP+9+qnE8pW2PUa0uhNipMTxlUbCsPgrh0qDyVMC6ZfVFuDQoPa3DtKzeCWeD2pOJZXLcg7XBw9MyDNt6u6XBy5OBFCkaPD2lkCB5g7enhHNLajZoeIohQrKGHxqeIugxpQ0avuRD67/Y2qD0lJLsCtuanqIADlUVDR6+2it9rX8VQgjxW+33XuuSd9I3IcSfN1pThmRr+/Bz57WHX/XzN1fHXwgA3fn2/FJje3d5GrVimZKMdrxhmZGOmXxfOmod007zx7yB/ztoArd4fsYxKwdzHP8w4BzXg2l9PxtwOzB03dAcdqo3u2QHtpc6vqw4uvWvevvCcdWxLRR/qubProc8Do6Ba0U5X7kS0Dm7Dt9x1a/mhrVPz6HBqTW7XpsaCvIO8Og7H0/eXjTNxdlk/EKf9Gd/AZGyCtsl8LzyAAAAAElFTkSuQmCC"
    }, "55b6": function (t, i, e) {
        "use strict";
        e.r(i);
        var s = function () {
            var t = this, i = t._self._c;
            return i("div", [t.$bankUtil.hasHeader ? i("jw-header", {
                ref: "header",
                attrs: {"l-click": "yes"},
                on: {"left-click": t.navigateBack}
            }, [t._v(t._s(t.data.equityPointActivityVO.equityName))]) : t._e(), t.data.equityPointActivityVO.activityStyle ? i("div", {staticClass: "qyd-banner"}, [i("van-image", {
                staticClass: "qyd-banner-image",
                attrs: {src: t.data.equityPointActivityVO.activityStyle}
            })], 1) : t._e(), i("div", {
                staticClass: "btn-order-list",
                on: {click: t.toOrderList}
            }, [t._v("订单记录"), i("van-image", {
                staticClass: "arrow",
                attrs: {src: t.arrow, fit: "contain"}
            })], 1), i("div", {staticClass: "qyd-goods-list"}, [i("div", {staticClass: "qyd-part-title"}, [t._v("权益项目")]), t._l(t.data.equityPointActivityProductList, (function (e, s) {
                return i("div", {
                    key: e.skuCode + s,
                    staticClass: "goods"
                }, [i("div", {staticClass: "goods-image"}, [i("van-image", {
                    attrs: {
                        src: t._f("imagesUrlReplace")(e.skuImg),
                        fit: "cover",
                        "lazy-load": ""
                    }
                }), e.skuStock < 1 && !t.isSpecialSku[e.skuCode] ? i("div", {staticClass: "sold-out"}, [t._v(t._s(t.soldOutText))]) : t._e()], 1), i("div", {staticClass: "goods-content"}, [i("div", {staticClass: "goods-title"}, [t._v(t._s(e.spuName))]), [parseFloat(e.skuPrice) ? i("div", {staticClass: "goods-price"}, [i("span", [t._v(t._s(e.skuEquityPoint))]), t._v("权益点+" + t._s(t._f("filterPrice")(e.skuPrice, 3)) + "元")]) : i("div", {staticClass: "goods-price"}, [i("span", [t._v(t._s(e.skuEquityPoint))]), t._v("权益点")])]], 2), i("div", {
                    staticClass: "btn-exchange",
                    // class: {disabled: e.skuStock < 1 || t.isSpecialSku[e.skuCode]},
                    on: {
                        click: function (i) {
                            return t.handleExchange(e)
                        }
                    }
                }, [t._v("立即兑换")])])
            })), i("div", {staticClass: "checkbox"}, [i("van-checkbox", {
                scopedSlots: t._u([{
                    key: "icon",
                    fn: function (t) {
                        return [t.checked ? i("img", {
                            staticClass: "img-icon",
                            attrs: {src: e("38cc")}
                        }) : i("img", {staticClass: "img-icon", attrs: {src: e("5fd0")}})]
                    }
                }]), model: {
                    value: t.isAgree, callback: function (i) {
                        t.isAgree = i
                    }, expression: "isAgree"
                }
            }, [i("span", [t._v("我已阅读并同意")]), i("span", {
                staticClass: "link", on: {
                    click: function (i) {
                        return i.stopPropagation(), t.toPrivacyAgreement.apply(null, arguments)
                    }
                }
            }, [t._v("《隐私协议》")])])], 1)], 2), t.data.equityPointActivityVO.activityDesc ? i("div", {
                staticClass: "qyd-detail",
                style: {background: t.data.equityPointActivityVO.backgroundColor || "#FFF"}
            }, [i("div", {staticClass: "qyd-part-title"}, [t._v("权益详情")]), i("div", {
                staticClass: "qyd-detail-content",
                class: {"hide-more": t.showMoreMask}
            }, [i("preview", {attrs: {value: t.data.equityPointActivityVO.activityDesc}})], 1), t.showMoreMask ? i("div", {
                staticClass: "show-more",
                on: {
                    click: function (i) {
                        t.showMoreMask = !1
                    }
                }
            }, [t._v("点击展开更多"), i("i", {staticClass: "icon-arrow"})]) : t._e()]) : t._e(), i("popup-tip", {
                attrs: {
                    visible: t.visibleWaitPay,
                    title: "您有未支付订单，请先支付~",
                    okLabel: "去支付"
                }, on: {
                    "update:visible": function (i) {
                        t.visibleWaitPay = i
                    }, ok: t.toOrderDetail
                }
            })], 1)
        }, a = [], o = e("c276"), c = e("a1bf"), d = e("1f36"), r = e("c852"), n = e("1f65"), u = e("3628"), l = {
            components: {Preview: n["a"], PopupTip: u["a"]}, data() {
                return {
                    arrow: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxOC4yNjEiIGhlaWdodD0iMTguMjYxIiB2aWV3Qm94PSIwIDAgMTguMjYxIDE4LjI2MSI+DQogIDxkZWZzPg0KICAgIDxzdHlsZT4NCiAgICAgIC5jbHMtMSB7DQogICAgICAgIGZpbGw6IG5vbmU7DQogICAgICAgIHN0cm9rZTogI2ZmZjsNCiAgICAgICAgc3Ryb2tlLXdpZHRoOiAxLjVweDsNCiAgICAgIH0NCiAgICA8L3N0eWxlPg0KICA8L2RlZnM+DQogIDxwYXRoIGlkPSLnrq3lpLQiIGNsYXNzPSJjbHMtMSIgZD0iTTEyLjE2MywwVjEyLjE2M0gwIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDkuMTMxKSByb3RhdGUoLTQ1KSIvPg0KPC9zdmc+DQo=",
                    data: {
                        equityPointActivityProductList: [],
                        equityPointActivityVO: [],
                        isAuth: !1,
                        userEquityPoint: 0
                    },
                    activityId: "",
                    isAgree: !1,
                    visibleWaitPay: !1,
                    mainOrderId: 0,
                    showMoreMask: !0,
                    channelId: "",
                    channelIdList: [],
                    activityIdList: [],
                    skuCodeList: [],
                    intervalId: null,
                    isSpecialSku: {},
                    soldOutText: "已售罄"
                }
            }, created() {
                document.body.style.backgroundColor = "#FFF", this.setSoldOutText()
            }, beforeDestroy() {
                document.body.style.backgroundColor = ""
            }, mounted() {
                this.channelIdList = JSON.parse('["43"]'), this.activityIdList = JSON.parse('["QYD1773181664042815488", "QYD1773181989436919808", "QYD1773182272200118272"]'), this.skuCodeList = JSON.parse('["SKU0027050", "SKU0027049", "SKU0027051"]'), this.channelId = this.$getSessionStorage("channelId"), this.activityId = this.$route.query.id, this.getActivityDetail()
            }, methods: {
                setSoldOutText() {
                    const t = (new Date).getHours();
                    this.soldOutText = t >= 12 ? "已售罄" : "即将开始"
                }, navigateBack() {
                    d["Collector"].record({
                        key: r["V"],
                        productId: this.data.equityPointActivityVO.activityId
                    }), Object(o["I"])(this)
                }, updateSpecialSkuStatus() {
                    if (!(Array.isArray(this.data.equityPointActivityProductList) && this.data.equityPointActivityProductList.length > 0)) return;
                    const t = () => {
                        this.data.equityPointActivityProductList.forEach(({skuCode: t}) => {
                            let i = this.channelIdList.includes(this.channelId) && this.activityIdList.includes(this.activityId) && this.skuCodeList.includes(t) && (new Date).getHours() < 12;
                            this.$set(this.isSpecialSku, t, i)
                        })
                    };
                    t(), this.intervalId && clearInterval(this.intervalId), this.intervalId = setInterval(t, 1e3)
                }, getActivityDetail() {
                    Object(c["a"])(this.activityId).then(t => {
                        200 === t.status && t.data && "0" === t.data.code ? (this.data = t.data.value || {}, this.updateSpecialSkuStatus(), document.title = this.data.equityPointActivityVO.equityName, this.$setTitle(this.data.equityPointActivityVO.equityName), d["Collector"].collectPvAndUv(), this.$nextTick(() => {
                            let t = document.querySelector(".ql-editor");
                            if (t) {
                                let i = window.getComputedStyle(t).height;
                                i = parseInt(i), this.showMoreMask = i > 300
                            }
                        })) : this.$toast(t.data.message)
                    }).catch(t => {
                        console.error(t)
                    })
                }, handleExistEquityPointOrder() {
                    return new Promise((t, i) => {
                        Object(c["f"])().then(e => {
                            200 === e.status && e.data && "0" === e.data.code ? t(e) : i(e)
                        }).catch(t => {
                            console.error(t)
                        })
                    })
                }, async handleExchange(t) {
                    if (this.isSpecialSku[t.skuCode]) return this.$toast("活动时间暂未开始");
                    // if (t.skuStock < 1) return;
                    if (!this.isAgree) return this.$toast("请阅读并同意隐私协议");
                    if (!this.data.isAuth) return void Object(o["B"])(!0);
                    // let i = await this.handleExistEquityPointOrder().catch(t => {
                    //     if ("-99" === t.data.code) return this.visibleWaitPay = !0, this.mainOrderId = parseInt(t.data.message), null;
                    //     this.$toast(t.data.message)
                    // });
                    // if (!i) return;
                    let e = {activityId: this.data.equityPointActivityVO.activityId, skuCode: t.skuCode, zxId: t.zxId};
                    this.$router.push({
                        path: "/order/confirmEquityPoint",
                        query: {activityId: e.activityId, skuCode: e.skuCode, zxId: e.zxId}
                    })
                }, toOrderList() {
                    this.$router.push({
                        path: "/orderList",
                        query: {orderStatus: "all", equityPointActivityId: this.data.equityPointActivityVO.idNum}
                    })
                }, toPrivacyAgreement() {
                    this.data.equityPointActivityVO.agreementLinkUrl ? location.href = this.data.equityPointActivityVO.agreementLinkUrl : console.error("隐私协议url未配置！")
                }, toOrderDetail() {
                    this.$router.push({
                        path: "/orderDetail",
                        query: {mainOrderId: this.mainOrderId, referrer: "equityPoint"}
                    })
                }
            }
        }, A = l, g = (e("17b3"), e("2877")), y = Object(g["a"])(A, s, a, !1, null, "092e4f20", null);
        i["default"] = y.exports
    }, "5fd0": function (t, i) {
        t.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAAAAXNSR0IArs4c6QAAAFFQTFRFAAAAv7+/t7e3tbW1tra2s7Oztra2tLS0tbW1tbW1tLS0tbW1tbW1tbW1tbW1tLS0tbW1tbW1tLS0tbW1tbW1tLS0tbW1tbW1tLS0tbW1tbW1E0e9swAAABp0Uk5TABAgMD9AUF9gb3B/gJCfoK+wv8DP0N/g7/CuiqmJAAACH0lEQVRYw82XyZarIBRFQWJCBVLR2MH+/w99A4wmRg1gDd4duZbrNtz2HCE2RF6MeTxaaB8PYy5SJIkyLQtpjYrVlqZnVXojo9Qdm+LMV/3L5L2ztiyVEKosre2mKC77bx+f7mu9iFbq2o/JKLb1yxC9t+u/bTDhNrOpR/XNVEkbgtDrv38BqIu9NxY1AL9r/yoArt+yfAWg2vDvz9/rfPYAt9X3D1HNpoaVPJQAQ2TDywHgzZlygI9uduUB95rtFuAcP2xngHaRgGvKuF7f0iB7oE4b+BropxYFfJFmoAAYW166+TteLODkHIBMNSD95LbPCSD47YUQQgFeZIgfu8mml2AuxHVsIp1jQIdmKgBkjgEZNDXQiSzpAC1sXg2edbDiljoGSwMNUOYZKIHmuIEeKPIMKKAVACJTAI4bGI49ofsPqnC4kQ63sn7f8CkSFoE8Os7jUGYulO4vVpoCXI4B9zzRQ/5aH5Y3JiWF8z17uTGJATzvmV3ChcTjGiBLlWagmjLwFwAjNFMyxHm9JoXfxcAfm8R9QJISoI+Fef0HzBvT0McBzX4VcN8BXAzUdatQN8wG/HzT/wHgvvbrHnD4PtwPmP62RzjY5mbS7BIOIUq/y81GPreHqdXIzly1pKryUo10sNufGj3MVNWcTkoIdTqZmcgO37ffyM1WxUdNvbTDuvpgo9eOmqnqRGSvKnFlaWubpoOuaazVm77/AfSsStGBTWbwAAAAAElFTkSuQmCC"
    }
}]);
