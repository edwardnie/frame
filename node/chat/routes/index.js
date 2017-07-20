var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', function (req, res) {
    if(req.query.clearCookie){
        res.clearCookie('username');
        req.cookies.username = '';
    }
    if(req.cookies.username){
        res.redirect('/chat');
    }else{
        res.render('home', {title: 'chat login'});
    }

});

router.post('/', function (req, res) {

    //例如:res.cookie('name', 'laodoujiao', { domain: '.cnblog.com', path: '/admin', secure: true,expires: new Date(Date.now() + 900000), httpOnly: true,maxAge:900000 });
    //注意maxAge这个参数，这是为了方便设置cookie的过期时间而设置的一个简易参数，已毫秒为单位
    if (req.body.password == '123456') {
        res.cookie('username', req.body.username, {maxAge: 900000});
        res.redirect('/chat');
    }else{
        res.redirect('/');
    }
});

/* GET home page. */
router.get('/chat', function (req, res) {
    if(req.cookies.username){
        res.render('index',{username:req.cookies.username});
    }else{
        res.redirect('/');
    }

});

module.exports = router;
