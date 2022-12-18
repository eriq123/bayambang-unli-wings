import "./bootstrap";

$(".select__status").on("change", function (e) {
    $(this).closest("form").submit();
});
