/**
 * 连街视频websocket
 * */


var connenctVide =function(cid){

    var i = 0;
    var localVideo = document.getElementById('localVideo');
    var remoteVideo = document.getElementById('remoteVideo');

    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    var configuration = {
        iceServers: [{
            urls: [
                'turn:turn.laychat.net:3478?transport=udp',
                'turn:turn.laychat.net:3478?transport=tcp',
                'turn:turn.laychat.net:3479?transport=udp',
                'turn:turn.laychat.net:3479?transport=tcp'
            ],
            username: 'rtc.laychat.net',
            credential: 'c1105d655c4bf95b1519c84e3be53669ba3f9b3d'
        }]
    };
    var pc, localStream;
    var answer = 0;
    var status = 0;

    navigator.mediaDevices.getUserMedia({
        audio: true,
        video: true
    }).then(function (stream) {
        mediaStreamTrack = stream;
        localVideo.srcObject = stream;
        localStream = stream;
    }).catch(function (e) {
        switch (e.name){
            case 'PermissionDeniedError':
                alert('请同意打开摄像头');
                break;
            case 'DevicesNotFoundError':
                alert('未找到摄像头');
                break;
        }
    });

    localVideo.addEventListener('loadedmetadata', function () {
       

        var channel = pusher.subscribe('private-video-room-' + cid);

        channel.bind('pusher:subscription_succeeded', function () {

            channel.trigger('client-call', null);
            channel.bind('client-already', function (e) {
                ++i;
                if (i == 2){
                    alert('房间已满，请更换房间');
                }
            });
            
            channel.bind('client-call', function (e) {
                if (status == 1){
                    channel.trigger('client-already', null);
                    return;
                }
                
                icecandidate(localStream);
                pc.createOffer({
                    offerToReceiveAudio: 1,
                    offerToReceiveVideo: 1
                }).then(function (desc) {
                    pc.setLocalDescription(desc).then(
                        function () {
                            channel.trigger('client-offer', pc.localDescription);
                        }
                    );
                });
            });
            channel.bind('client-answer', function (data) {
                pc.setRemoteDescription(new RTCSessionDescription(data));
            });
        });

        channel.bind('client-offer', function (data) {
            icecandidate(localStream);
            pc.setRemoteDescription(new RTCSessionDescription(data));
            if (!answer) {
                pc.createAnswer().then(function (desc) {
                        pc.setLocalDescription(desc).then(function () {
                            channel.trigger('client-answer', pc.localDescription);
                        });
                    }
                );
                answer = 1;
            }
        });

        function icecandidate(localStream) {
            pc = new RTCPeerConnection(configuration);
            pc.onicecandidate = function (event) {
                if (event.candidate) {
                    channel.trigger('client-candidate', event.candidate);
                }
            };
            channel.bind('client-candidate', function (data) {
                pc.addIceCandidate(new RTCIceCandidate(data));
            });
            try {
                var tracks = localStream.getTracks();
                for(var i=0;i<tracks.length;i++){
                    pc.addTrack(tracks[i], localStream);
                }
            } catch(e) {
                pc.addStream(localStream);
            }
            pc.onaddstream = function (e) {
                $('#remoteVideo').removeClass('hidden');
                $('#localVideo').remove();
                remoteVideo.srcObject = e.stream;
            };
            pc.oniceconnectionstatechange = function(e) {
                switch (pc.iceConnectionState){
                    case 'connected':
                        status = 1;
                        break;
                    case 'disconnected':
                        status = 0;
                        break;
                }
            };
        }

        window.onbeforeunload = function () {
            channel.trigger('client-close', null);
        };
    });


}