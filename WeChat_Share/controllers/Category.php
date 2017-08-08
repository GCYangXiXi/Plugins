<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends My_Controller {

        public static $signature;

        //手机app分类页面
        function Category()
        {
                parent::__construct();
                $this->base = $this->config->item("base_url");
                $this->load->model('model_func');
                $this->load->model('model_common');
                $this->load->model('common');
                $this->load->helper("page");
                $this->load->helper("func");
                $this->load->helper("glenn");
                $this->load->library('pagination');
                $this->load->library('session');
                $this->is_mobile = "/^(\+\d{2,3}\-)?\d{11}$/";
                $this->is_pwd = "/^[a-z,A-Z,0-9]{6,20}$/";
                //获取微信配置信息
                include dirname(dirname(dirname(__FILE__))) . '/jssdk.php';
                self::$signature = JSSDK::getInstance();
        }

        //外层菜单页
        function index()
        {
                $data['info'] = $this->common->findAllByField('category', 'id,img,cate_name', "pid=0 and id!=6", "sortnum desc,id asc");
                foreach ($data['info'] as $k => $v)
                {
                        $data['info'][$k]['next'] = $this->common->findAllByField('category', 'id,cate_name', "pid=" . $v['id'], "sortnum desc,id asc");
                }
                $this->load->view('category', $data);
        }

        //判断当前地址（省市区）
        function getlocal()
        {
                if ($_SESSION['user_id'] > 0)
                {
                        $user_id = $_SESSION['user_id'];
                        $u_arr = $this->common->findOne('user', 'id=' . $user_id, 'pro_id,city_id,area_id');
                        if ($u_arr['pro_id'] > 0 && $u_arr['city_id'] > 0 && $u_arr['area_id'] > 0)
                        {
                                $l_arr = $_SESSION['local'] = array('pro_id' => $u_arr['pro_id'], 'city_id' => $u_arr['city_id'], 'area_id' => $u_arr['area_id']);
                        }
                        else
                        {
                                $l_arr = $_SESSION['local'] = array('pro_id' => 3, 'city_id' => 36, 'area_id' => 408);
                        }
                }
                else
                {
                        $l_arr = $_SESSION['local'];
                        if ($l_arr['pro_id'] > 0 && $l_arr['city_id'] > 0 && $l_arr['area_id'] > 0)
                        {
                                
                        }
                        else
                        {
                                $l_arr = $_SESSION['local'] = array('pro_id' => 3, 'city_id' => 36, 'area_id' => 408);
                        }
                }
                return $l_arr;
        }

        //分类详情页（商家列表）
        function cate_details($c_pid, $c_id)
        {
                $data['c_id'] = $c_id;
                $data['l_arr'] = $l_arr = $this->getlocal();
                $data['c'] = $this->common->findOne('category', "id=" . $c_pid, 'cate_name');
                if ($c_pid != 6)
                {
                        if ($c_pid == 7)
                        {
                                $data['cate'] = $this->common->findAllByField('category', 'id,cate_name', 'pid=7 or pid = 5', 'sortnum desc,id asc');
                                if ($c_id > 0)
                                {
                                        $sids = $c_id;
                                        //从分类页进来的
                                        $data['banner'] = $this->common->findAllByField('cate_adv', 'pic,url', "c_id=" . $c_id . " and pro_id=" . $l_arr['pro_id'] . " and city_id=" . $l_arr['city_id'] . " and area_id=" . $l_arr['area_id'], "sortnum desc,addtime desc");
                                }
                                else
                                {
                                        $sids = $data['cate'][0]['id'];
                                        //第一个小分类的banner
                                        $data['banner'] = $this->common->findAllByField('cate_adv', 'pic,url', "c_id=" . $data['cate'][0]['id'] . " and pro_id=" . $l_arr['pro_id'] . " and city_id=" . $l_arr['city_id'] . " and area_id=" . $l_arr['area_id'], "sortnum desc,addtime desc");
                                }
                                //只读出当前区域的商家
                                $where = "pro_id=" . $l_arr['pro_id'] . " and city_id=" . $l_arr['city_id'] . " and area_id=" . $l_arr['area_id'] . " and c_id=" . $sids . " and is_ok=1 and is_sel=0";
                                $data['seller'] = $this->common->findAllByField('seller', 'id,click,seller_name,outpic,order_nums,h_zb,z_zb,s_time,x_time', $where, "addtime desc");
                        }
                        else
                        {
                                //二级分类
                                $data['cate'] = $this->common->findAllByField('category', 'id,cate_name', "pid=" . $c_pid, "sortnum desc,id asc");
                                if ($c_id > 0)
                                {
                                        //从外层分类页进来的
                                        $sids = $c_id;
                                        $data['banner'] = $this->common->findAllByField('cate_adv', 'pic,url', "c_id=" . $c_id, "sortnum desc,addtime desc");
                                }
                                else
                                {
                                        $sids = $data['cate'][0]['id'];
                                        //第一个小分类的banner
                                        $data['banner'] = $this->common->findAllByField('cate_adv', 'pic,url', "c_id=" . $data['cate'][0]['id'], "sortnum desc,addtime desc");
                                }
                                //只读出当前区域的商家
                                $where = "pro_id=" . $l_arr['pro_id'] . " and city_id=" . $l_arr['city_id'] . " and area_id=" . $l_arr['area_id'] . " and c_id=" . $sids . " and is_ok=1 and is_sel=0";
                                $data['seller'] = $this->common->findAllByField('seller', 'id,click,seller_name,outpic,order_nums,h_zb,z_zb,s_time,x_time', $where, "addtime desc");
                        }
                }
                else
                {
                        //当地特色（没有二级类）
                        $data['banner'] = $this->common->findAllByField('cate_adv', 'pic,url', "c_pid=6", "sortnum desc,addtime desc");
                        //只读出当前区域的商家
                        $where = "pro_id=" . $l_arr['pro_id'] . " and city_id=" . $l_arr['city_id'] . " and area_id=" . $l_arr['area_id'] . " and c_pid=6 and is_ok=1";
                        $data['seller'] = $this->common->findAllByField('seller', 'id,click,seller_name,outpic,order_nums,h_zb,z_zb,s_time,x_time', $where, "addtime desc");
                }
                $data['as'] = $this->common->findOne('areas', 'id=' . $l_arr['area_id'], 'area_name');
                $this->load->view('cate_details', $data);
        }

        //根据展示的类别显示轮播图和列表
        function showinfo()
        {
                $id = $_POST['id'];
                //当前分类的banner
                $data['banner'] = $this->common->findAllByField('cate_adv', 'pic,url', "c_id=" . $id, "sortnum desc,addtime desc");
                $l_arr = array(
                        'pro_id' => $_POST['pro_id'],
                        'city_id' => $_POST['city_id'],
                        'area_id' => $_POST['area_id']
                );
                $where = "pro_id=" . $l_arr['pro_id'] . " and city_id=" . $l_arr['city_id'] . " and area_id=" . $l_arr['area_id'] . " and c_id=" . $id . " and is_ok=1 and is_sel=0";
                $data['seller'] = $this->common->findAllByField('seller', 'id,click,seller_name,outpic,order_nums,area_id,h_zb,z_zb', $where, "addtime desc");
                foreach ($data['seller'] as $k => $v)
                {
                        $data['seller'][$k]['area'] = $this->common->findOne('areas', 'id=' . $v['area_id'], 'area_name');
                }
                echo json_encode($data);
        }

        //商家信息
        function seller($id)
        {
                $data['user_id'] = $user_id = $_SESSION['user_id'];
                $data['info'] = $this->common->findOne('seller', "id=" . $id . " and is_ok=1", 'id,c_id,click,seller_name,user_id,pro_id,city_id,area_id,address,outpic,inpic,is_fee,is_show,pay_type,h_zb,z_zb,intro');
                $data['info']['addr'] = $this->getpca($data['info']['pro_id'], $data['info']['city_id'], $data['info']['area_id']) . '　' . $data['info']['address'];
                //头部展示该商家所属分类的广告轮播图
                $data['adv'] = $this->common->findAllByField('cate_adv', 'pic,url', "c_id=" . $data['info']['c_id']);
                //浏览量+1
                $ll = array('click' => $data['info']['click'] + 1);
                $this->model_common->getupd('seller', $id, $ll);
                $user_id == '' ? $user_idss = 0 : $user_idss = $user_id;
                $cl = array('seller_id' => $id, 'user_id' => $user_idss, 'addtime' => time());
                $this->model_common->getadd('seller_click', $cl);
                //当前商家的手机号
                $data['info']['u_arr'] = $this->common->findOne('user', "id=" . $data['info']['user_id'], 'mobile,headimg');
                $data['imgs'] = $this->common->findAllByField('seller_imgs', 'img', "seller_id=" . $id, 'addtime desc');
                foreach ($data['imgs'] as $v)
                {
                        $data['simgs'] .= _BASE_ . $v['img'] . ',';
                }
                //判断是否是自己的店铺
                if ($data['info']['user_id'] == $user_id)
                {
                        $data['zj'] = 1;
                }
                //判断当前用户是否收藏该店铺
                if ($user_id > 0)
                {
                        $rs = $this->model_func->getone('collect_seller', "user_id=" . $user_id . " and seller_id=" . $id);
                        if ($rs)
                        {
                                $data['is_c'] = 1;
                        }
                        else
                        {
                                $data['is_c'] = 0;
                        }
                }
                else
                {
                        $data['is_c'] = 0;
                }
                $data['s_banner'] = _BASE_ . $data['info']['outpic'] . ',' . _BASE_ . $data['info']['inpic'];
                $data['goods'] = $this->common->findAllByField('goods', 'id,name,click,img,sell_price,market_price,sale_nums', "seller_id=" . $id, 'sortnums desc,addtime desc');
                $data['signature'] = self::$signature->getSignPackage();
                $this->load->view('seller', $data);
        }

        //商品详情
        function goods_detail($id)
        {
                $data['user_id'] = $user_id = $_SESSION['user_id'];
                $data['info'] = $this->common->findOne('goods', "id=" . $id, 'id,click,name,sell_price,market_price,sale_nums,content,buy_book,nums,seller_id');
                //查询店铺营业时间
                $data['selltime'] = $this->common->findOne('seller', 'id=' . $data['info']['seller_id'], 's_time,x_time');
                //浏览量+1
                $ll = array('click' => $data['info']['click'] + 1);
                $this->model_common->getupd('goods', $id, $ll);
                $user_id == '' ? $user_idss = 0 : $user_idss = $user_id;
                $cl = array('goods_id' => $id, 'user_id' => $user_idss, 'addtime' => time());
                $this->model_common->getadd('goods_click', $cl);
                $data['seller'] = $this->common->findOne('seller', "id=" . $data['info']['seller_id'], 'user_id');
                $data['seller']['u_arr'] = $this->common->findOne('user', "id=" . $data['seller']['user_id'], 'mobile,username,headimg');
                $data['imgs'] = $this->common->findAllByField('goods_photo', 'img', "goods_id=" . $id);
                $data['img_intro'] = $this->common->findAllByField('goods_intro', 'img', "goods_id=" . $id);
                foreach ($data['imgs'] as $v)
                {
                        $data['gimgs'] .= _BASE_ . $v['img'] . ',';
                }
                //评价
                $data['com'] = $this->common->findAllByField('comment', 'user_id,point,comment,comment_time', "goods_id=" . $id);
                foreach ($data['com'] as $k => $v)
                {
                        $data['com'][$k]['u_arr'] = $this->common->findOne('user', 'id=' . $v['user_id'], 'username,headimg');
                }
                //判断当前用户是否收藏该店铺
                if ($user_id > 0)
                {
                        $rs = $this->model_func->getone('collect_goods', "user_id=" . $user_id . " and goods_id=" . $id);
                        if ($rs)
                        {
                                $data['is_c'] = 1;
                        }
                        else
                        {
                                $data['is_c'] = 0;
                        }
                }
                else
                {
                        $data['is_c'] = 0;
                }
                $data['info']['buy_book'] = str_replace("/public/kindeditor/attached/", "http://ww.slh98.com/public/kindeditor/attached/", $data['info']['buy_book']);
                $data['info']['content'] = str_replace('/public/kindeditor/attached/', "http://www.slh98.com/public/kindeditor/attached/", $data['info']['content']);
                $data['info']['content'] = str_replace('&quot', "", $data['info']['content']);
                $data['signature'] = self::$signature->getSignPackage();
                $this->load->view('goods_detail', $data);
        }

        //详细地址
        function getpca($p, $c, $a)
        {
                $p = $this->model_func->getone('areas', 'id=' . $p);
                $c = $this->model_func->getone('areas', 'id=' . $c);
                $a = $this->model_func->getone('areas', 'id=' . $a);
                return $p['area_name'] . '-' . $c['area_name'] . '-' . $a['area_name'];
        }

        //ajax加入购物车操作
        function addcart()
        {
                $goods_id = $_POST['goods_id'];
                $user_id = $_POST['user_id'];
                //判断该用户的资料是否完善
                $u_arr = $this->common->findOne('user', 'id=' . $user_id, 'truename,pca,tradepass');
                $certify = $this->common->findOne('certify', 'user_id=' . $user_id, 'state');
                //$account = $this->model_func->getone('account','user_id='.$user_id);
                //$address = $this->model_common->getall('address',"user_id=".$user_id." and state=1");
                $res = $this->common->findOne('cart', "goods_id=" . $goods_id . " and user_id=" . $user_id);
                if ($res)
                {
                        //数量加1
                        $ar = array('nums' => $res['nums'] + 1);
                        $s = $this->model_common->getupd('cart', $res['id'], $ar);
                }
                else
                {
                        $goods = $this->common->findOne('goods', "id=" . $goods_id, 'seller_id');
                        $ar = array('user_id' => $user_id, 'goods_id' => $goods_id, 'nums' => 1, 'seller_id' => $goods['seller_id'], 'addtime' => time());
                        $s = $this->model_common->getadd('cart', $ar);
                }
                if ($s)
                {
                        echo 'ok';
                }
                else
                {
                        echo 'no';
                }
        }

        //导航商家地图
        function gomap($id)
        {
                $data['s_arr'] = $this->common->findOne('seller', 'id=' . $id, 'id,h_zb,z_zb,seller_name');
                $this->load->view('gomap', $data);
        }

        //收藏操作
        function collect()
        {
                $str = $_POST['str'];
                $user_id = $_POST['user_id'];
                if ($str == 'seller')
                {
                        //收藏商家
                        $seller_id = $_POST['seller_id'];
                        $rs = $this->model_func->getone('collect_seller', "seller_id=" . $seller_id . " and user_id=" . $user_id);
                        if ($rs)
                        {
                                //取消收藏
                                $res = $this->model_common->del('collect_seller', "id=" . $rs['id']);
                        }
                        else
                        {
                                //收藏
                                $arr = array(
                                        'seller_id' => $seller_id,
                                        'user_id' => $user_id,
                                        'addtime' => time()
                                );
                                $res = $this->model_common->getadd('collect_seller', $arr);
                        }
                }
                else if ($str == 'goods')
                {
                        //收藏商品
                        $goods_id = $_POST['goods_id'];
                        $rs = $this->model_func->getone('collect_goods', "goods_id=" . $goods_id . " and user_id=" . $user_id);
                        if ($rs)
                        {
                                //取消收藏
                                $res = $this->model_common->del('collect_goods', "id=" . $rs['id']);
                        }
                        else
                        {
                                //收藏
                                $arr = array(
                                        'goods_id' => $goods_id,
                                        'user_id' => $user_id,
                                        'addtime' => time()
                                );
                                $res = $this->model_common->getadd('collect_goods', $arr);
                        }
                }
                if ($res)
                {
                        echo 'ok';
                }
                else
                {
                        echo 'no';
                }
        }

        //根据2点经纬度计算距离
        function getDistance($lng1, $lat1, $lng2, $lat2)
        {
                $pi = 3.1415926;
                $earthRadius = 6378137;
                //approximate radius of earth in meters
                /*  Convert these degrees to radians  to work with the formula  */
                $lat1 = ($lat1 * pi() ) / 180;
                $lng1 = ($lng1 * pi() ) / 180;
                $lat2 = ($lat2 * pi() ) / 180;
                $lng2 = ($lng2 * pi() ) / 180;
                /*  Using the  Haversine formula    http://en.wikipedia.org/wiki/Haversine_formula    calculate the distance  */
                $calcLongitude = $lng2 - $lng1;
                $calcLatitude = $lat2 - $lat1;
                $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
                $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
                $calculatedDistance = $earthRadius * $stepTwo;
                return round($calculatedDistance);
        }

}
