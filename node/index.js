// express framework for a basic http server
var app = require("express")();
// create the http server
var http = require("http").createServer(app);
// require the socket.io and bind it to a port
var io = require("socket.io")(3000);
//jwt auth
//var jwtAuth = require('socketio-jwt-auth');

//require mysql for db connection
const mysql = require('mysql');


var led = "0";
let gUserId;


const connection = mysql.createConnection({
    host: 'localhost',
    user: 'dbUsername',
    password: 'dbPassword',
    database: 'dbName'
});

connection.connect((err) => {
    if (err) throw err;
    console.log('Connected to database!');
});

var connected;
console.log("bir seyler oldu!!");

io.attach(http, {
    pingInterval: 10000,
    pingTimeout: 5000,
    cookie: false,
});


io.on("connection", function (socket) {

    connected = "1";

    socket.on("userLogin", function (msg) {
        let uptData = [
            msg.socketid,
            msg.userid
        ]
        connection.query(
            'UPDATE users SET lastsocketid = ? Where id = ?', uptData,
            (err, result) => {
                if (err) throw err;

                console.log(`Last Socket id updated ${result.changedRows} row(s)`);

            }
        );
        gUserId = msg.userid;
        socket.join(msg.userid);


    });


    var device = {
        id: socket.id,
        mac: "",
        time: new Date().toISOString().replace(/T/, ' ').replace(/\..+/, '')
    };

    socket.on("macAddress", function (msg) {
        device.mac = msg;
        console.log("Mac: " + device.mac);
        deviceOnline();

    });


    function deviceOnline() {
        connection.query(
            'UPDATE devices SET online = ?, socketid = ?, onlinedate =? Where mac = ?',
            [connected, device.id, device.time, device.mac],
            (err, result) => {
                if (err) throw err;

                console.log(`Changed ${result.changedRows} row(s)`);
            }
        );

        connection.query("SELECT *, users.id as userid FROM users LEFT JOIN devices ON users.id = devices.userid WHERE devices.socketid = ?", [socket.id], function (err, result) {
            if (err) throw err;
            result = JSON.stringify(result);
            result = JSON.parse(result);
            username = result[0].name;
            usersocketid = result[0].lastsocketid;
            userid1 = result[0].userid;
            socket.join(gUserId); //device userid indexli socket room'a join oldu
            gUserId = userid1;
        });


    }


    console.log("connection time:" + device.time);

    console.log("id: " + device.id); // ojIckSD2jqNzOqIrAGzL


//User disconnected
    socket.on("disconnect", function () {
        connected = "0";
        console.log("user disconnected: " + device.id);
        io.emit("id", device.id);

        connection.query(
            'UPDATE devices SET online = ? Where mac = ?',
            ['0', device.mac],
            (err, result) => {
                if (err) throw err;

                console.log(`Changed ${result.changedRows} row(s)`);
            }
        );


        console.log("user disconnected at : " + new Date().toISOString().replace(/T/, ' ').replace(/\..+/, ''))
    });

    //device dan gelen mesaj
    socket.on("message", function (msg) {
        console.log("esp: " + msg);
    });

    socket.on("watering", function (msg) {
        var socketids;

        connection.query("SELECT * FROM devices WHERE mac = ?", [msg.device], function (err, result) {
            if (err) throw err;
            //console.log(result);

            result.forEach(function (row) {
                socketids = row.socketid;
            });
            console.log(socketids);

            //-----------------------
            io.to(gUserId).emit("watering", msg.status);
            console.log("water: " + msg.mac + " id: " + gUserId);
            if (msg.status)
                io.to(gUserId).emit("reply", "ACIK");
            else
                io.to(gUserId).emit("reply", "KAPALI");

            let insData = [
                {
                   pomp : msg.status
                },
                {
                    mac: msg.mac
                }
            ];

            connection.query('UPDATE devices SET ? WHERE ?', insData, (err, res) => {
                if (err) throw err;
                console.log(`POMP CHANGED, ${res.changedRows} row(s)`);
            });


            //-----------------------

        });


    });

    socket.on("envData", function (msg) {
        msg.mois = (msg.mois*100)/500;
        if(msg.mois>100)
            msg.mois = 100;
        var data = {
            sensors: msg,
            id: socket.id

        }
        io.to(gUserId).emit("envData", data);
        io.emit("envData", data);

        console.log(msg);


    });

    socket.on("sensorfail", function (msg) {
        console.log(msg);
        var username;
        connection.query("SELECT *, users.id as userid FROM users LEFT JOIN devices ON users.id = devices.userid WHERE devices.socketid = ?", [socket.id], function (err, result) {
            if (err) throw err;
            result = JSON.stringify(result);
            result = JSON.parse(result);
            username = result[0].name;
            usersocketid = result[0].lastsocketid;
            userid1 = result[0].userid;

            console.log("hataaaa" + username);
            io.to(userid1).emit("sensorfail", msg);
        });

    });

    socket.on("envDataSave", function (msg) {
        //sensör verilerini database e yazan fonksiyon
        var macaddrr;

        var datenow = new Date().toISOString().replace(/T/, ' ').replace(/\..+/, '');

        connection.query("SELECT * FROM devices WHERE socketid = ?", [socket.id], function (err, result) {
            if (err) throw err;
            result = JSON.stringify(result);
            result = JSON.parse(result);

            var sql = "INSERT INTO measures (deviceid, mac, moisture,humidity,temperature,registerdate) VALUES (?,?,?,?,?,?)";
            connection.query(sql, [result[0].id, result[0].mac, msg.mois, msg.humid, msg.temp, datenow], function (err, result) {
                if (err) throw err;

                console.log("1 record inserted to database measures!!");
            });

        });


    });

    socket.on("minMois", function (msg) {
        socket.to(gUserId).emit("serverminMois", msg);
    });

    socket.on("maxMois", function (msg) {
        socket.to(gUserId).emit("servermaxMois", msg);
    });


    socket.on("humid", function (msg) {
        var humiddata = {
            temp: msg,
            mac: socket.id

        }
        io.emit("humid", humiddata);
        console.log("humid: " + msg);
    });

    socket.on("moistureRange", function (msg) {
        console.log("Range: " + msg.min + msg.max + " socketid :" + socket.id);
        let insData = [
            {
                minMois: msg.min,
                maxMois: msg.max
            },
            {
                mac: msg.mac
            }
        ];

        connection.query('UPDATE devices SET ? WHERE ?', insData, (err, res) => {
            if (err) throw err;
            console.log(`Changed ${res.changedRows} row(s)`);
        });

        connection.query("SELECT * FROM devices WHERE mac = ? AND online= '1' ", [msg.mac], function (err, result) {
            if (err) throw err;
            result = JSON.stringify(result);
            result = JSON.parse(result);
            console.log("İŞTEE :" + result[0].socketid);
            //io.to(result[0].socketid).emit("minMois", msg.min);
            //io.to(result[0].socketid).emit("maxMois", msg.max);
            io.to(result[0].socketid).emit("minmaxMois", msg.max+";"+msg.min);


        });


    });


// timeout();
})
;

function timeout() {
    if (connected == "1") {


        if (led == "0") {
            setTimeout(function () {
                io.emit("reply", "ACIK");
                led = "1";
                //  console.log("ledon");
                timeout();
            }, 5000);
        } else {
            setTimeout(function () {
                io.emit("reply", "KAPALI");
                led = "0";
                //console.log("ledoff");
                timeout();
            }, 100);
        }
    }

}

http.listen();
