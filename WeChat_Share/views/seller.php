<!DOCTYPE html>
<html>
        <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
                <link rel="stylesheet" type="text/css" href="/wechat/public/default/css/basic-phone.css">
                <link rel="stylesheet" type="text/css" href="/wechat/public/default/css/style.css">
                <script src="/wechat/public/default/js/rem.js"></script>
                <script src="/wechat/public/default/js/jquery.min.js" ></script>
                <script src="/wechat/public/default/js/slick.min.js" ></script>
                <title>商家详情</title>
                <style type="text/css">
                        .shopping{
                                width: 100%;
                                height: 1.05rem;
                                background-color: #f3f3f3;
                                text-align: center;
                                line-height: 1.05rem;
                                color: #87000f;
                        }

                        .shopping_hope{
                                margin-top: .5rem;
                        }
                        .shopping_hope div{
                                display: inline-block;
                                margin-left: 5%;
                        }
                        .shopping_hope_one {
                                float: left;
                                margin-left: 5%;
                        }
                        .shopping_hope_one img{
                                width: 3rem;
                                height: 3.9rem;
                        }
                        .sped{
                                margin-top: .2rem;
                                padding-left: .2rem;
                                font-weight: bold;
                                width: 3.2rem;
                                height: .5rem;
                                overflow: hidden;  /*溢出隐藏*/
                                text-overflow: ellipsis; /*以省略号...显示*/
                                white-space: nowrap;  /*强制不换行*/
                        }
                        .sped_two{
                                margin-top: .3rem;
                                width: 3.2;
                                height: 1rem;
                        }
                        .sped_two span:nth-last-of-type(1){
                                color: #b15a63;
                                margin-left: .6rem;
                        }
                        .sped_two span:nth-last-of-type(2){
                                color: #a5a5a5;
                                margin-left: -.2rem;
                        }
                </style>

        </head>
        <body style="background: #F3F3F3;">
                <header id="s_head">
                        <a onclick="history.go(-1)" class="iconfont icon left">&#xe616;</a>
                        <h3>商家详情</h3>
                </header>
                <!--该类别banner-->
                <section id="pro_banner" style="margin-top: .88rem;">
                        <div class="slider single-item" style="height: 3.69rem;">
                                <?php
                                foreach ($adv as $v)
                                {
                                        ?>
                                        <div>
                                                <?php
                                                if ($v['url'] != '' && $v['url'] != '0')
                                                {
                                                        ?>
                                                        <a href="<?php echo $v['url']; ?>">
                                                        <?php } ?>
                                                        <img src="<?php echo $v['pic']; ?>" style="width:100%;">
                                                        <?php
                                                        if (isset($v['url']) && $v['url'] != '0')
                                                        {
                                                                ?>
                                                        </a>
                                                <?php } ?>
                                        </div>
                                <?php } ?>
                        </div>
                </section>

                <section class="pro_nav clearfix">
                        <dl class="clearfix">
                                <dd class="active"><h3>全部商品</h3></dd>
                                <dd><h3>商家信息</h3></dd>
                        </dl>
                </section>

                <script>
                        function collect_seller(seller_id)
                        {
                                var user_id = "<?php echo $user_id; ?>";
                                if (user_id > 0)
                                {
                                        $.ajax({
                                                type: "POST",
                                                url: '/wechat/category/collect',
                                                data: {user_id: user_id, seller_id: seller_id, str: 'seller'},
                                                async: false,
                                                success: function (data) {

                                                        if (data == 'ok')
                                                        {
                                                                if ($(".cang").hasClass('active'))
                                                                {
                                                                        $(".cang").removeClass('active');
                                                                        warn('取消成功！');
                                                                } else
                                                                {
                                                                        $(".cang").addClass('active');
                                                                        warn('收藏成功！');
                                                                }
                                                        } else
                                                        {
                                                                warn('操作失败！');
                                                        }
                                                }
                                        })
                                } else
                                {
                                        warn('您还未登录！');
                                }
                        }
                </script>

                <!--全部商品-->
                <section class="pro_son clearfix" >
                        <?php
                        if ($goods)
                        {
                                foreach ($goods as $v)
                                {
                                        ?>
                                        <div class="shopping_hope_one">
                                                <a href="/wechat/category/goods_detail/<?php echo $v['id']; ?>"><img src="<?php echo $v['img']; ?>"/><br /></a>
                                                <div class="sped" style="font-size: .40rem;"><?php echo $v['name']; ?></div> 
                                                <div class="sped_two" style="font-size: .25rem;overflow: hidden;text-align: center;">¥：<?php echo 1 * $v['sell_price']; ?><br><del style="color: red;">市场价：<?php echo $v['market_price']; ?></del></div>
                                        </div>
                                        <?php
                                }
                        }
                        else
                        {
                                ?>
                                <div id="nonerecord1">没有记录</div>
                        <?php } ?>
                </section>

                <!--商家信息-->
                <section class="pro_son clearfix" style="display:none;">
                        <section class="shop_infor clearfix">
                                <div style="background: #FFFFFF; padding: .2rem;" class="clearfix mt2">
                                        <h3 style="color:#333;font-size:.35rem;"><?php echo $info['seller_name']; ?>
                                                <span style="float:right;font-size:.25rem;">浏览量：<?php echo $info['click'] + 1; ?></span>
                                        </h3>

                                        <h5 class="fl" style="margin-left:0;">
                                                <?php echo $info['is_fee'] == 1 ? '已加入同城配送中心' : ''; ?>
                                        </h5>
                                        <h5 class="fr">
                                                <?php echo $info['is_show'] == 1 ? ($info['pay_type'] == 1 ? '快递：商家付' : '快递：客户到付') : ''; ?>
                                        </h5>
                                </div>
                                <div class="clear"></div>

                                <!--商家轮播图-->
                                <section id="pro_banner" style="margin-top: 0;position:relative;">
                                        <div class="slider single-item" style="height: 3.69rem;">
                                                <div><span><img src="<?php echo $info['outpic']; ?>" class="imgTest"></span></div>
                                                <div><span><img src="<?php echo $info['inpic']; ?>" class="imgTest"></span></div>
                                        </div>
                                        <script type="text/javascript">
                                                $.fn.ImgZoomIn = function () {
                                                        bgstr = '<div id="ImgZoomInBG" style=" background:#000000; filter:Alpha(Opacity=70); opacity:0.7; position:fixed; left:0; top:0; z-index:10000; width:100%; height:100%; display:none;"><iframe src="about:blank" frameborder="5px" scrolling="yes" style="width:100%; height:100%;"></iframe></div>';
                                                        imgstr = '<img id="ImgZoomInImage" src="' + $(this).attr('src') + '" onclick=$(\'#ImgZoomInImage\').hide();$(\'#ImgZoomInBG\').hide(); style="cursor:pointer; display:none; position:absolute; z-index:10001;" />';
                                                        if ($('#ImgZoomInBG').length < 1) {
                                                                $('body').append(bgstr);
                                                        }
                                                        if ($('#ImgZoomInImage').length < 1) {
                                                                $('body').append(imgstr);
                                                        } else {
                                                                $('#ImgZoomInImage').attr('src', $(this).attr('src'));
                                                        }
                                                        $('#ImgZoomInImage').css('left', $(window).scrollLeft() + ($(window).width() - $('#ImgZoomInImage').width()) / 2);
                                                        $('#ImgZoomInImage').css('top', $(window).scrollTop() + ($(window).height() - $('#ImgZoomInImage').height()) / 2);
                                                        $('#ImgZoomInBG').show();
                                                        $('#ImgZoomInImage').show();
                                                };
                                                $(document).ready(function () {
                                                        $(".imgTest").bind("click", function () {
                                                                $(this).ImgZoomIn();
                                                        });
                                                });
                                        </script>
                                        <i style="position:absolute;right:.2rem;top:.2rem;z-index:999;" onclick="collect_seller(<?php echo $info['id']; ?>)" class="iconfont icon fr cang <?php echo $is_c == 1 ? 'active' : ''; ?>" >&#xe625;</i>
                                        <div style="position:absolute;bottom:10px;right:5%;color:#fff;background-color:#e2252b; padding:.05rem .2rem;border-radius:.06rem;">点击图片查看</div>
                                </section>
                                <div class="tets clearfix">
                                        <i class="iconfont icon fl sit" >&#xe60e;</i>
                                        <a href="/wechat/category/gomap/<?php echo $info['id']; ?>">
                                                <h4 class="fl" ><?php echo $info['addr']; ?></h4>
                                        </a>
                                </div>
                                <div class="tets clearfix">
                                        <p style="width:70%;margin:0 15%;">
                                                <a href="/wechat/category/gomap/<?php echo $info['id']; ?>">
                                                        <span style="padding:.05rem 0;border-radius:.1rem;float:left;width:30%;text-align:center;background:red;color:#fff;">到这里去</span>
                                                </a>
                                                <a href="tel:<?php echo $info['u_arr']['mobile']; ?>">
                                                        <span style="padding:.05rem 0;border-radius:.1rem;float:right;width:30%;text-align:center;background:red;color:#fff;">联系商家</span>
                                                </a>
                                        </p>
                                </div>

                                <div style="background: #FFFFFF; padding:.1rem .2rem;" class="clearfix mt2">
                                        <h2>商家介绍</h2>
                                        <?php
                                        if ($info['intro'])
                                        {
                                                ?> 
                                                <p><?php echo $info['intro']; ?></p>
                                                <?php
                                        }
                                        else
                                        {
                                                ?>
                                                <div id="nonerecord1" style="margin:20% 0;">暂无介绍</div>
                                        <?php } ?>

                                        <div>
                                                <?php
                                                foreach ($imgs as $k => $v)
                                                {
                                                        ?>
                                                        <img src="<?php echo $v['img'] ?>" style="width:100%; display:block; margin-top:.1rem;" />
                                                <?php } ?>
                                        </div>

                                </div>
                        </section>
                </section>

                <div id="warn"></div>		

                <script type="text/javascript">
                        $(function () {
                                //banner轮播
                                $('.single-item').slick({
                                        dots: true,
                                        autoplay: true,
                                        autoplaySpeed: 2000
                                });
                                //选项卡
                                $(".pro_nav dd").click(function () {
                                        $(this).addClass('active').siblings().removeClass('active');
                                        var i = $(this).index();
                                        $(".pro_son").eq(i).show().siblings(".pro_son").hide();
                                });

                        });
                </script>
                <!--以下是微信分享链接JS-->
                <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
                <script>
                        wx.config({
                                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                                appId: '<?php echo $signature['appId']; ?>', // 必填，公众号的唯一标识
                                timestamp: <?php echo $signature['timestamp']; ?>, // 必填，生成签名的时间戳
                                nonceStr: '<?php echo $signature['nonceStr']; ?>', // 必填，生成签名的随机串
                                signature: '<?php echo $signature['signature']; ?>', // 必填，签名，见附录1
                                jsApiList: [
                                        'onMenuShareTimeline',
                                        'onMenuShareAppMessage'
                                ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
                        });
                        wx.ready(function () {
                                var shareData = {
                                        title: '<?php echo $info['seller_name']; ?>',
                                        desc: '<?php echo empty($info['intro']) ? '请点击查看' : $info['intro']; ?>',
                                        link: '<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>',
                                        imgUrl: '<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $info['outpic']; ?>',
                                        success: function () {
                                                // 用户确认分享后执行的回调函数
                                                alert('分享成功!');
                                        },
                                        cancel: function () {
                                                // 用户取消分享后执行的回调函数
                                                alert('取消分享');
                                        }
                                };
                                wx.onMenuShareAppMessage(shareData);
                                wx.onMenuShareTimeline(shareData);
                        });
                        wx.error(function (res) {
                                alert(res.errMsg);
                        });
                </script>
        </body>
</html>