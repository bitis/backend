(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["chunk-31620b2e"], {
    "08ab": function (t, e, a) {
        "use strict";
        var i = function () {
            var t = this, e = t._self._c;
            return e("div", {staticClass: "s-canvas"}, [e("canvas", {
                attrs: {
                    id: "s-canvas",
                    width: t.contentWidth,
                    height: t.contentHeight
                }
            })])
        }, s = [], o = {
            name: "SIdentify",
            props: {
                identifyCode: {type: String, default: ""},
                fontSizeMin: {type: Number, default: 20},
                fontSizeMax: {type: Number, default: 25},
                contentWidth: {type: Number, default: 90},
                contentHeight: {type: Number, default: 43},
                bgColor: {type: String, default: "#e6ecfd"},
                lineCount: {type: Number, default: 4},
                dotCount: {type: Number, default: 30},
                textAngleMin: {type: Number, default: -30},
                textAngleMax: {type: Number, default: 30},
                letterSpacing: {type: Number, default: 0}
            },
            methods: {
                randomNum(t, e) {
                    return Math.floor(Math.random() * (e - t) + t)
                }, randomColor(t, e) {
                    let a = this.randomNum(t, e), i = this.randomNum(t, e), s = this.randomNum(t, e);
                    return "rgb(" + a + "," + i + "," + s + ")"
                }, drawPic() {
                    let t = document.getElementById("s-canvas"), e = t.getContext("2d");
                    e.textBaseline = "bottom", e.fillStyle = this.bgColor, e.fillRect(0, 0, this.contentWidth, this.contentHeight);
                    for (let a = 0; a < this.identifyCode.length; a++) this.drawText(e, this.identifyCode[a], a);
                    this.drawLine(e), this.drawDot(e)
                }, drawText(t, e, a) {
                    t.fillStyle = this.randomColor(50, 160), t.font = this.randomNum(this.fontSizeMin, this.fontSizeMax) + "px SimHei";
                    const i = a ? this.letterSpacing : -3 * this.letterSpacing / 2;
                    let s = (a + 1) * (this.contentWidth / (this.identifyCode.length + 2) + i),
                        o = this.randomNum(this.fontSizeMax, this.contentHeight - 5);
                    var d = this.randomNum(this.textAngleMin, this.textAngleMax);
                    t.translate(s, o), t.rotate(d * Math.PI / 180), t.fillText(e, 0, 0), t.rotate(-d * Math.PI / 180), t.translate(-s, -o)
                }, drawLine(t) {
                    for (let e = 0; e < this.lineCount; e++) t.strokeStyle = this.randomColor(100, 200), t.beginPath(), t.moveTo(this.randomNum(0, this.contentWidth), this.randomNum(0, this.contentHeight)), t.lineTo(this.randomNum(0, this.contentWidth), this.randomNum(0, this.contentHeight)), t.stroke()
                }, drawDot(t) {
                    for (let e = 0; e < this.dotCount; e++) t.fillStyle = this.randomColor(0, 255), t.beginPath(), t.arc(this.randomNum(0, this.contentWidth), this.randomNum(0, this.contentHeight), 1, 0, 2 * Math.PI), t.fill()
                }
            },
            watch: {
                identifyCode() {
                    this.drawPic()
                }
            },
            mounted() {
                this.drawPic()
            }
        }, d = o, r = (a("6161"), a("2877")), n = Object(r["a"])(d, i, s, !1, null, "bcd7a52a", null);
        e["a"] = n.exports
    }, "27ea": function (t, e, a) {
    }, "2b1f": function (t, e, a) {
        "use strict";
        a("6ecf")
    }, "34b0": function (t, e, a) {
        "use strict";
        a("27ea")
    }, 6161: function (t, e, a) {
        "use strict";
        a("d696")
    }, "6ecf": function (t, e, a) {
    }, 7636: function (t, e, a) {
        "use strict";
        a.d(e, "a", (function () {
            return i
        }));
        const i = t => {
            let e = t;
            return t.includes("400001") && t.includes("可兑换商品总数量失败") || t.includes("400006") && t.includes("商品限制规则校验") && t.includes("商品月周期兑换失败") ? e = "本月商品已售罄" : (t.includes("400006") && t.includes("客户限制规则校验") && t.includes("商品月周期兑换失败") || t.includes("900004") && t.includes("限定周期可兑换次数不可大于总可兑换次数")) && (e = "本月已无可用兑换次数"), e
        }
    }, 9043: function (t, e, a) {
        "use strict";
        a.r(e);
        var i = function () {
                var t = this, e = t._self._c;
                return e("div", {staticClass: "confirmPage"}, [t.$bankUtil.hasHeader ? e("jw-header", {
                    attrs: {"l-click": "yes"},
                    on: {"left-click": t.navigateBack}
                }, [t._v("确认订单")]) : t._e(), t.data ? [e("div", {staticClass: "proList"}, [e("div", {staticClass: "shopGroup"}, [e("div", {staticClass: "shopName"}), e("div", {staticClass: "proGroup"}, [e("div", {staticClass: "proItem"}, [e("div", {staticClass: "pic"}, [e("van-image", {
                    attrs: {
                        width: "100%",
                        height: "100%",
                        src: t._f("imagesUrlReplace")(t.data.productSpecPicture)
                    }
                })], 1), e("div", {staticClass: "intro"}, [e("div", {staticClass: "intro-top"}, [e("div", {staticClass: "title van-multi-ellipsis--l2"}, [t._v(" " + t._s(t.data.productTitle) + " ")]), e("div", {staticClass: "spec"}, [t._v(t._s(t.data.productSpecTitle || "默认"))])]), [parseFloat(t.data.orderAmount) ? e("div", {staticClass: "price"}, [e("span", [t._v(t._s(t.data.equityPointNumber))]), t._v("权益点+" + t._s(t._f("filterPrice")(t.data.orderAmount, 3)) + "元")]) : e("div", {staticClass: "price"}, [e("span", [t._v(t._s(t.data.equityPointNumber))]), t._v("权益点")])]], 2), e("div", {staticClass: "num"}, [t._v("x" + t._s(t.data.number))])]), t.data.mobile ? e("div", [t._m(0), e("div", {staticClass: "cellGroup account"}, [t._m(1), e("div", {staticClass: "cellValue"}, [e("van-field", {
                    staticStyle: {"font-weight": "inherit"},
                    attrs: {maxlength: 11, readonly: ""},
                    model: {
                        value: t.data.mobile, callback: function (e) {
                            t.$set(t.data, "mobile", e)
                        }, expression: "data.mobile"
                    }
                })], 1)])]) : t._e(), t.data.iseCardFlag ? e("div", {
                    key: t.data.productSpecId + "ecard",
                    staticClass: "valid-code"
                }, [e("van-field", {
                    attrs: {
                        "input-align": "left",
                        maxlength: 6,
                        type: "digit",
                        placeholder: "请输入验证码"
                    }, scopedSlots: t._u([{
                        key: "button", fn: function () {
                            return [e("div", {
                                staticClass: "btn-code",
                                class: {sending: !t.isOverCountDown},
                                on: {
                                    click: function (e) {
                                        return e.preventDefault(), t.sendLoginCode()
                                    }
                                }
                            }, [t._v(t._s(t.isOverCountDown ? "发送验证码" : t.countDown + "s重获"))])]
                        }, proxy: !0
                    }], null, !1, 1968927494), model: {
                        value: t.params.eCode, callback: function (e) {
                            t.$set(t.params, "eCode", e)
                        }, expression: "params.eCode"
                    }
                })], 1) : t._e(), e("div", {staticClass: "valid-code"}, [e("van-field", {
                    attrs: {
                        maxlength: "4",
                        placeholder: "请输入图片验证码",
                        border: !1
                    }, model: {
                        value: t.params.validCode, callback: function (e) {
                            t.$set(t.params, "validCode", e)
                        }, expression: "params.validCode"
                    }
                }), e("validCode", {
                    attrs: {
                        identifyCode: t.dataValidCode,
                        bgColor: "#FFF",
                        lineCount: 0,
                        dotCount: 0,
                        textAngleMin: -3,
                        textAngleMax: 3,
                        fontSizeMin: 30,
                        fontSizeMax: 34,
                        letterSpacing: 2
                    }, nativeOn: {
                        click: function (e) {
                            return t.getValidCode.apply(null, arguments)
                        }
                    }
                })], 1)])])]), e("div", {staticClass: "analysis"}, [e("div", {staticClass: "cellGroup"}, [e("div", {staticClass: "cellTitle"}, [t._v("商品总额")]), [parseFloat(t.data.orderAmount) ? e("div", {staticClass: "cellValue"}, [e("span", [t._v(t._s(t.data.equityPointNumber))]), t._v("权益点+" + t._s(t._f("filterPrice")(t.data.orderAmount, 3)) + "元")]) : e("div", {staticClass: "cellValue"}, [e("span", [t._v(t._s(t.data.equityPointNumber))]), t._v("权益点")])]], 2), [parseFloat(t.data.orderAmount) ? e("div", {staticClass: "total"}, [t._v("需付款："), e("span", [t._v(t._s(t.data.equityPointNumber) + "权益点+" + t._s(t._f("filterPrice")(t.data.orderAmount, 3)) + "元")])]) : e("div", {staticClass: "total"}, [t._v("需付款："), e("span", [t._v(t._s(t.data.equityPointNumber) + "权益点")])])]], 2), t.orderTips ? e("div", {staticClass: "order-tips"}, [e("preview", {attrs: {value: t.orderTips}})], 1) : t._e(), e("div", {staticClass: "flexFooter"}, [e("van-sticky", [e("div", {staticClass: "footer"}, [[parseFloat(t.data.orderAmount) ? e("div", {staticClass: "price price-large"}, [e("span", [t._v(t._s(t.data.equityPointNumber))]), t._v("权益点+" + t._s(t._f("filterPrice")(t.data.orderAmount, 3)) + "元")]) : e("div", {staticClass: "price price-large"}, [e("span", [t._v(t._s(t.data.equityPointNumber))]), t._v("权益点")])], e("div", {
                    staticClass: "btn",
                    on: {click: t.handleSubmit}
                }, [t._v("提交订单")])], 2)])], 1), e("popup-tip", {
                    attrs: {
                        visible: t.popupAddrChange,
                        title: "默认充值到注册手机号"
                    }, on: {
                        "update:visible": function (e) {
                            t.popupAddrChange = e
                        }, ok: t.handleSubmitOrder
                    }
                }), e("popup-tip", {
                    attrs: {visible: t.popupCancelOrder, title: "确定取消支付该订单？"},
                    on: {
                        "update:visible": function (e) {
                            t.popupCancelOrder = e
                        }, ok: function (e) {
                            return t.$router.back()
                        }
                    }
                })] : t._e()], 2)
            }, s = [function () {
                var t = this, e = t._self._c;
                return e("div", {staticClass: "cellGroup"}, [e("div", {staticClass: "cellTitle"}, [t._v("充值类型")]), e("div", {staticClass: "cellValue"}, [t._v("手机号码")])])
            }, function () {
                var t = this, e = t._self._c;
                return e("div", {staticClass: "cellTitle"}, [t._v("充值账号"), e("span", [t._v("（动卡空间注册手机号）")])])
            }], o = a("a1bf"), d = a("08ab"), r = a("1f65"), n = a("3628"), l = a("7b20"), c = a("2241"), u = a("7636"),
            h = a("c276"), p = a("de4a"), m = {
                name: "confirmComplianceGift", components: {validCode: d["a"], Preview: r["a"], PopupTip: n["a"]}, data() {
                    return {
                        data: null,
                        popupAddrChange: !1,
                        popupCancelOrder: !1,
                        params: {activityId: "", skuCode: "", validCode: "", eCode: "", zxId: ""},
                        preview: !1,
                        orderTips: "",
                        dataValidCode: "",
                        timer: null,
                        countDown: "",
                        isOverCountDown: !0
                    }
                }, mounted() {
                    let t = this.$route.query.activityId, e = this.$route.query.skuCode, a = this.$route.query.zxId;
                    if (!t || !e) return this.$toast({message: "参数错误", forbidClick: !0}), void setTimeout(() => {
                        this.$router.go(-1)
                    }, 1e3);
                    this.preview = !!this.$route.query.preview, this.params.activityId = t, this.params.skuCode = e, this.params.zxId = a, this.getData(), this.handleGetOrderTips(), this.getValidCode()
                }, methods: {
                    navigateBack() {
                        this.popupCancelOrder = !0
                    }, sendLoginCode(t) {
                        if (!this.isOverCountDown) return !1;
                        Object(p["u"])({mobile: this.data.mobile, specId: this.data.productSpecId}).then(t => {
                            if (0 != t.data.code) return this.$toast(t.data.message);
                            t.data.value ? this.timer || (this.countDown = 60, this.isOverCountDown = !1, this.timer = setInterval(() => {
                                this.countDown > 0 && this.countDown <= 60 ? this.countDown-- : (this.isOverCountDown = !0, clearInterval(this.timer), this.timer = null)
                            }, 1e3)) : Object(c["a"])({
                                title: "提示",
                                className: "dia429",
                                confirmButtonText: "我知道了",
                                message: "获取验证码失败，请检查此手机号是否已在京东进行实名认证，如未实名认证，请先在京东实名认证后重试！"
                            }).then(() => {
                            })
                        })
                    }, getData() {
                        let t = this.params.activityId, e = this.params.skuCode, a = this.params.zxId;
                        Object(o["b"])({activityId: t, skuCode: e, zxId: a}).then(t => {
                            // if (200 !== t.status || !t.data || "0" !== t.data.code) {
                            //     const e = "商品库存已售罄，请下次再来参与。";
                            //     return t.data.message === e || t.message === e ? void c["a"].alert({message: e}).then(() => {
                            //         this.$router.go(-1)
                            //     }) : (this.$toast({
                            //         message: t.data.message || t.message,
                            //         forbidClick: !0
                            //     }), void setTimeout(() => {
                            //         this.$router.go(-1)
                            //     }, 1e3))
                            // }
                            this.data = t.data.value
                        }).catch(t => {
                            console.error(t)
                        })
                    }, handleSubmit() {
                        let t = {...this.params};
                        try {
                            if (this.data.iseCardFlag && !t.eCode) return void this.$toast("请输入验证码");
                            if (!t.validCode) return void this.$toast("请输入图片验证码");
                            if (t.validCode !== this.dataValidCode) return void this.$toast("请输入正确的图片验证码");
                            this.data.mobile ? this.popupAddrChange = !0 : this.handleSubmitOrder()
                        } catch (e) {
                            return console.error(e)
                        }
                    }, handleSubmitOrder() {
                        Object(o["c"])(this.params).then(t => {
                            if (200 !== t.status || !t.data) return this.$toast(t.message);
                            "0" === t.data.code ? this.handlePay(t.data.value) : (["-10001", "-10002"].includes(t.data.code) && this.getValidCode(), this.$myToast.show(t.data.message))
                        }).catch(t => {
                        })
                    }, async handlePay(t) {
                        try {
                            let e = await Object(l["E"])({mainOrderId: t});
                            e && e.data && e.data.success ? parseFloat(this.data.orderAmount) ? location.replace(e.data.value.payUrl) : Object(o["g"])(t).then(t => {
                                this.toOrderDetail(t.data.value)
                            }).catch(t => {
                                this.toOrderList()
                            }) : e && e.data.message ? "400003" === e.data.code ? Object(h["B"])(!0) : c["a"].confirm({
                                message: Object(u["a"])(e.data.message),
                                className: "equity-point-dialog",
                                confirmButtonText: "确定",
                                showCancelButton: !1
                            }).then(() => {
                                this.toOrderList()
                            }).catch(() => {
                            }) : this.$toast("支付失败")
                        } catch (e) {
                            console.error(e)
                        }
                    }, toOrderDetail(t) {
                        this.$router.replace({path: "/orderDetail", query: {subOrderId: t, referrer: "equityPoint"}})
                    }, toOrderList() {
                        this.$router.replace({path: "/orderList", query: {orderStatus: "all"}})
                    }, getValidCode() {
                        this.preview || this.antiShake || (this.antiShake = !0, Object(o["d"])().then(t => {
                            if (200 !== t.status || !t.data || "0" !== t.data.code || !t.data.value) return this.$toast(t.data.message || t.message);
                            this.$nextTick(() => {
                                this.dataValidCode = t.data.value
                            })
                        }).finally(() => {
                            setTimeout(() => {
                                this.antiShake = !1
                            }, 1e3)
                        }))
                    }, handleGetOrderTips() {
                        Object(l["B"])().then(t => {
                            if (200 !== t.status || !t.data) return this.$toast(t.message);
                            "0" === t.data.code ? this.orderTips = t.data.value : this.$toast(t.data.message)
                        })
                    }
                }
            }, v = m, f = (a("34b0"), a("2b1f"), a("2877")), C = Object(f["a"])(v, i, s, !1, null, "32374902", null);
        e["default"] = C.exports
    }, d696: function (t, e, a) {
    }
}]);
