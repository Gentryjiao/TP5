/**
 * @file: Kz.layedit开发者测试服务器
 * @author: mqycn <https://gitee.com/mqycn>
 */

const express = require('express');
const path = require('path');
const fs = require('fs');
const multer = require('multer');
const bodyParser = require('body-parser');

const listenPort = 5566;
const app = express();

const rootPath = path.join(__dirname, '..');
const imagePath = 'imgs';

app.use(bodyParser.urlencoded({
    extended: false
}));
app.use(multer({
    dest: path.join(rootPath, imagePath, 'temp')
}).array('file'));


// 记录请求
const log = {
    request(req) {
        console.log(`\n\n${req.method} ${req.path}\nRequest`, {
            query: req.query,
            params: req.params,
            body: req.body,
            files: req.files
        });
    },
    response(resp, json) {
        console.log('Response', {
            headers: resp.headers,
            body: json
        });
    }
};

// 输出json
const createPage = (page) => (req, resp) => {
    log.request(req);
    return page(req, resp, json => {
        json.code = json.msg === 'ok' ? 0 : 1;
        resp.header({
            'content-type': 'application/json'
        });
        log.response(resp, json);
        resp.send(JSON.stringify(json));
        resp.end();
    });
};

// 为了少该文件，上传接口使用 http://localhost:7777/your url
app.post('/your%20url', createPage((req, resp, result) => {
    if (req.files && req.files.length > 0) {
        // 文件上传流程
        const {
            path: tempPath,
            originalname
        } = req.files[0];

        const fileExt = originalname.split('.').splice(-1) || 'jpg';
        const saveFile = `${(new Date().getTime())}${parseInt(Math.random() * 10000, 10)}.${fileExt}`;
        const savePath = path.join(rootPath, imagePath, saveFile);
        fs.copyFile(tempPath, savePath, (err) => {
            if (err) {
                result({
                    msg: `上传错误：${err}`
                });
            } else {
                fs.unlink(tempPath, () => {
                    console.log(`删除：${tempPath}`);
                });
                result({
                    msg: 'ok',
                    data: {
                        src: [imagePath, saveFile].join('/'),
                        do: 'upload'
                    }
                });
            }
        });
    } else {
        // 没有上传文件，判断是不是删除操作
        if (req.body && req.body.imgpath) {
            const imageUrl = req.body.imgpath.replace(req.headers.origin, '');
            fs.unlink(path.join.apply(null, [rootPath, ...imageUrl.split('\\')]), (err) => {
                if (err) {
                    result({
                        msg: `删除错误：${err}`
                    });
                } else {
                    result({
                        msg: 'ok',
                        do: 'delete'
                    });
                }
            });
        } else {
            result({
                msg: '未知的请求'
            });
        }
    }
}));


// empty.html
app.get('/empty.html', createPage((req, resp) => {
    resp.send('');
}));

// imgs开头，使用静态模式可能会出现图片打不开
app.get(`/${imagePath}/*`, createPage((req, resp, result) => {
    log.request(req);
    const filePath = path.join(rootPath, imagePath, req.params[0]);
    if (fs.existsSync(filePath)) {
        fs.readFile(filePath, (err, data) => {
            if (err) {
                result({
                    msg: `Error: /${err}`
                });
            } else {
                log.response(resp, {
                    get: filePath
                });
                resp.write(data);
                resp.end();
            }
        });
    } else {
        result({
            msg: `File Not Found: /${imagePath}/${req.params[0]}`
        });
    }
}));

// kz.layEdit 编辑器调试
app.use(express.static(rootPath));

app.listen(listenPort, () => {
    console.log(`启动成功，请访问 http://localhost:${listenPort} 测试`);
});