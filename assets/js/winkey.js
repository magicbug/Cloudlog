let sendText = document.getElementById("sendText");
let sendButton = document.getElementById("sendButton");
let receiveText = document.getElementById("receiveText");
let connectButton = document.getElementById("connectButton");
let statusBar = document.getElementById("statusBar");

//Couple the elements to the Events
connectButton.addEventListener("click", clickConnect)
sendButton.addEventListener("click", clickSend)
helpButton.addEventListener("click", clickHelp)
statusButton.addEventListener("click", clickStatus)

//When the connectButton is pressed
async function clickConnect() {
    if (port) {
        //if already connected, disconnect
        disconnect();

    } else {
        //otherwise connect
        await connect();
    }
}

//Define outputstream, inputstream and port so they can be used throughout the sketch
var outputStream, inputStream, port;
navigator.serial.addEventListener('connect', e => {
    statusBar.innerText = `Connected to ${e.port}`;
    connectButton.innerText = "Disconnect"
});
  
navigator.serial.addEventListener('disconnect', e => {
    statusBar.innerText = `Disconnected`;
    connectButton.innerText = "Connect"
});

//Connect to the serial
async function connect() {

    //Optional filter to only see relevant boards
    const filter = {
        usbVendorId: 0x2341 // Arduino SA
    };

    //Try to connect to the Serial port
    try {
        port = await navigator.serial.requestPort(/*{ filters: [filter] }*/);
        // Continue connecting to |port|.

        // - Wait for the port to open.
        await port.open({ baudRate: 1200 });

        statusBar.innerText = "Connected";
        connectButton.innerText = "Disconnect"

        let decoder = new TextDecoderStream();
        inputDone = port.readable.pipeTo(decoder.writable);
        inputStream = decoder.readable;

        const encoder = new TextEncoderStream();
        outputDone = encoder.readable.pipeTo(port.writable);
        outputStream = encoder.writable;

        reader = inputStream.getReader();
        readLoop();
    } catch (e) {
        //If the pipeTo error appears; clarify the problem by giving suggestions.
        if (e == "TypeError: Cannot read property 'pipeTo' of undefined") {
            e += "\n Use Google Chrome and enable-experimental-web-platform-features"
        }
        connectButton.innerText = "Connect"
        statusBar.innerText = e;
    }
}

//Write to the Serial port
async function writeToStream(line) {
    var enc = new TextEncoder(); // always utf-8
    
    const writer = outputStream.getWriter();
    writer.write(enc);
    writer.releaseLock();
}

//Disconnect from the Serial port
async function disconnect() {

    if (reader) {
        await reader.cancel();
        await inputDone.catch(() => { });
        reader = null;
        inputDone = null;
    }
    if (outputStream) {
        await outputStream.getWriter().close();
        await outputDone;
        outputStream = null;
        outputDone = null;
    }
    statusBar.innerText = "Disconnected";
    connectButton.innerText = "Connect"
    //Close the port.
    await port.close();
    port = null;
}

//When the send button is pressed
function clickSend() {
    writeToStream(sendText.value);
    writeToStream("\r");
    
    //and clear the input field, so it's clear it has been sent
    sendText.value = "";

}

//Read the incoming data
async function readLoop() {
    while (true) {
        const { value, done } = await reader.read();
        if (done === true){
            break;
        }

        console.log(value);
        //When recieved something add it to the big textarea
        receiveText.value += value;
        //Scroll to the bottom of the text field
        receiveText.scrollTop = receiveText.scrollHeight;
    }
}