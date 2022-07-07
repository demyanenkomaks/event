$(document).ready(function(){
    $('.card-box').on('click', '.js-message-response', function () {
        $('.js-id-message-response').val(this.parentElement.parentElement.getAttribute('data-id-message'));
        $('#js-textarea-response').val(this.parentElement.parentElement.getAttribute('data-message-response'));
        $('.js-text-message-form-response').text(this.parentElement.parentElement.getElementsByClassName('js-text-message')[0].innerText);

        $('#js-modal-response').modal('show');
    });

    $('.card-box').on('click', '.js-question-speaker', function () {
        $('.js-id-message-speaker').val(this.parentElement.parentElement.getAttribute('data-id-message'));
        $('.js-id-speaker').val(this.parentElement.parentElement.getAttribute('data-id-speaker')).change();
        $('.js-text-message-form-speaker').text(this.parentElement.parentElement.getElementsByClassName('js-text-message')[0].innerText);

        $('#js-modal-speaker').modal('show');
    });
});

var obConnection = function () {
    this.hostname = window.location.hostname;
    this.socket;
    this.reconnectId = 0;
    this.reconnectCount = 0;
    this.reconnectMaxCount = 10;
    this.takeInWorkMessage = '.js-take-in-work-message';
    this.approvedMessage = '.js-approved-message';
    this.rejectedMessage = '.js-rejected-message';
    this.responseButtonForm = '.js-button-form-response';
    this.speakerButtonForm = '.js-button-form-speaker';
    this.init();
}

obConnection.prototype = {
    init: function () {
        this.connect();
        $('.card-box').on('click', this.takeInWorkMessage,  this.executeTakeInWorkMessage.bind(this)); // Взятие в работу сообщения
        $('.card-box').on('click', this.approvedMessage,  this.executeApprovedMessage.bind(this)); // Одобрение сообщения
        $('.card-box').on('click', this.rejectedMessage,  this.executeRejectedMessage.bind(this)); // Отклонение сообщения
        $(this.responseButtonForm).on('click', this.executeFormResponseMessage.bind(this)); // Ответить на сообщение
        $(this.speakerButtonForm).on('click', this.executeFormSpeakerNote.bind(this)); // Отметить спикера
    },
    connect: function () {
        this.socket = new WebSocket('wss://' + this.hostname + '/wss2/backend?app=moderationChat');
        this.socket.onopen = this.onOpen.bind(this);

        console.log("Соединение установлено!");
    },
    onOpen: function (e) {
        if (this.reconnectId) {
            console.log('Соединение восстановлено!');
            clearInterval(this.reconnectId);
            this.reconnectId = 0;
            this.reconnectCount = 0;
        }
        this.socket.onclose = this.onClose.bind(this);
        this.socket.onmessage = this.onMessage.bind(this);
    },
    onClose: function (e) {
        console.log('Соединение потеряно!');

        if (!e.wasClean || e.code !== 1000) {
            // повторные попытки подключиться каждые 5 минут в течении 15 минут
            this.reconnectId = setInterval(this.reconnect.bind(this), 5000);
        }
    },
    reconnect: function () {
        if (this.reconnectCount === this.reconnectMaxCount) {
            clearInterval(this.reconnectId);
        }
        this.connect();
        this.reconnectCount++;
        console.log('Попытка переподлкючиться #' + this.reconnectCount);
    },
    sendMessage: function (data) {
        this.socket.send(JSON.stringify(data));
    },
    executeTakeInWorkMessage: function(e) {
        var btn = $(e.currentTarget);

        this.sendMessage({
            action: 'takeInWorkMessage',
            id: btn.parent().parent().data('id-message')
        });
    },
    executeApprovedMessage: function(e) {
        var btn = $(e.currentTarget);

        this.sendMessage({
            action: 'approvalMessage',
            id: btn.parent().parent().data('id-message')
        });
    },
    executeRejectedMessage: function(e) {
        var btn = $(e.currentTarget);

        this.sendMessage({
            action: 'rejectedMessage',
            id: btn.parent().parent().data('id-message')
        })
    },
    executeFormResponseMessage: function(e) {
        this.sendMessage({
            action: 'responseMessage',
            id: $('.js-id-message-response').val(),
            message: $('#js-textarea-response').val(),
        });

        $('#js-modal-response').modal('hide');

        $('.js-id-message-response').val('');
        $('#js-textarea-response').val('');
    },
    executeFormSpeakerNote: function(e) {
        this.sendMessage({
            action: 'speakerNote',
            id: $('.js-id-message-speaker').val(),
            idSpeaker: $('.js-id-speaker').val(),
        });

        $('#js-modal-speaker').modal('hide');

        $('.js-id-message-speaker').val('')
        $('.js-id-speaker').val('')
    },
    onMessage: function (e) {
        var data = JSON.parse(e.data);

        switch (data.action) {
            case 'moderationMessage':
                $.pjax.reload({container: '#pjax-content', timeout: false});
                break;
        }
    },
}

window.Connection = new obConnection();
