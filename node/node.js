var buf1 = new Buffer(10);
buf = new Buffer(26);
console.log(buf);

for (var i = 0 ; i < 26 ; i++) {
    buf[i] = i + 97; // 97 is ASCII a
}

buf.copy(buf, 2, 1, 5);
console.log(buf.toString());

// efghijghijklmnopqrstuvwxyz