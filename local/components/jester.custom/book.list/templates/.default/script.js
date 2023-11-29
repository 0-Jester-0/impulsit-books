window.onload = function () {
    $("#bookList").on("click", ".btn-outline-success", function (event) {
        const targetButton = $(this);

        const form = $(this).context.form;

        const bookId = form.querySelector("input[name='book-id']").value;
        const mark = $(this).val();

        //Вызов метода компонента "rateBookAction" через ajax
        BX.ajax.runComponentAction("jester.custom:book.list", "rateBook", {
            mode: "class",
            data: {
                bookId,
                mark
            }
        }).then(function (status) {
            console.log(status);

            manageButtonsActivity(targetButton, form)
            recalculateAverageBookRating(bookId);
        }).catch(function (error) {
            console.log(error);
        })
    })
}

function manageButtonsActivity (targetButton, form) {
    let activeButton = $(form).find(".btn-outline-success.active");

    //Удаляет подсвечивание кнопки с выставленной ранее оценкой (если такая есть)
    if (activeButton || targetButton.id === activeButton.id) {
        activeButton.removeClass("active");
    }

    //Если нажатая кнопка оценки уже активна, класс активности удаляется, а оценка удаляется
    if (targetButton.val() === activeButton.val()) {
        targetButton.removeClass("active");
    } else {
        targetButton.addClass("active");
    }
}

function recalculateAverageBookRating (bookId) {
    ////Вызов метода компонента "rateBookAction" через ajax
    BX.ajax.runComponentAction("jester.custom:book.list", "recalculateAverageBookRating", {
        mode: "class",
        data: {
            bookId
        }
    }).then(function (status) {
        console.log(status)

        $("#book-" + bookId + "-rating").find("strong").text(status.data)
    }).catch(function (error) {
        console.log(error)
    })
}