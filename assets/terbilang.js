"use strict";

/**
 *
 * jQuery Plugin $().terbilang()
 * @author : Chabib Nurozak <chabibdev@gmail.com>
 * @see : https://github.com/chabibnr
 *
 * Function Source
 * @link : https://github.com/titounnes/kumpulan_library/blob/master/js/terbilang.js
 * @uthor : Harjito
 * @see : https://github.com/titounnes
 */

(function ($) {
  $.fn.terbilang = function (options) {
    var defaults = {
        output: "text",
        nominal: 0,
      },
      settings = $.extend({}, defaults, options);

    var digit3 = function (feed) {
      //mendefinisikan satuan
      var units = [
        "",
        "ribu ",
        "juta",
        "milyar",
        "triliun",
        "kuadriliun",
        "kuantiliun",
        "sekstiliun",
        "septiliun",
        "oktiliun",
        "noniliun",
        "desiliun",
      ];
      //Mendefinisikan bilangan
      var angka = [
        "",
        "satu",
        "dua",
        "tiga",
        "empat",
        "lima",
        "enam",
        "tujuh",
        "delapan",
        "sembilan",
      ];
      //membuat function untuk memecah bilangan menjadi array beranggota 3 digit

      var number = currency(feed).split(",");

      //menginisiasi luaran
      var output = "";

      var segment3 = number[0].split(".");
      //Membilang setiap segmen 3 digit
      $.each(segment3, function (i, v) {
        if (v * 1 != 0) {
          //memecah 3 digit menjadi arrau 1 digit
          if (v.length < 3) {
            v = ("00" + v).substr(-3, 3);
          }
          var digit = v.split("");

          //menentukan nilai ratusan
          output +=
            digit[0] == "1"
              ? "seratus"
              : digit[0] != "0"
              ? angka[digit[0]] + " ratus "
              : "";

          //menentukan nilai puluhan
          if (digit[1] == "1") {
            output +=
              digit[2] == "0"
                ? " sepuluh "
                : (digit[2] == "1" ? " se" : angka[digit[2]]) + "belas ";
          } else if (digit[1] != "0") {
            output += angka[digit[1]] + " puluh " + angka[digit[2]] + " ";
          } else {
            if (digit[0] == "0" && digit[1] == "0" && digit[2] == "1") {
              output += i == segment3.length - 2 ? "se" : "satu ";
            } else {
              output += angka[digit[2]] + " ";
            }
          }
          output += units[segment3.length - i - 1] + " ";
        }
      });

      var decimal = "";
      if (typeof number[1] != "undefined") {
        decimal = " koma ";
        angka[0] = " nol";
        $.each(number[1].split(""), function (i, v) {
          decimal += " " + angka[v];
        });
      }

      return output + decimal;
    };

    var currency = function (feed, number) {
      if (typeof number != "undefined" && !isNaN(number)) {
        feed = (Math.round((feed * 10) ^ -number) * 10) ^ number;
      }

      var segment = feed.split(".");

      while (/(\d+)(\d{3})/.test(segment[0])) {
        segment[0] = segment[0].replace(/(\d+)(\d{3})/, "$1" + "." + "$2");
      }

      return (
        segment[0] + (typeof segment[1] != "undefined" ? "," + segment[1] : "")
      );
    };

    this.each(function (index, row) {
      var el = $(row);
      var getOutput = el.data("output");
      getOutput = getOutput || settings.output;
      var nominal =
        el.data("nominal") || (el.text() != "" ? el.text() : settings.nominal);
      nominal = String(nominal);
      console.log(nominal);
      if ("text" == getOutput) {
        el.text(digit3(nominal));
      } else if ("digit" == getOutput) {
        el.text(currency(nominal));
      } else {
        el.text("Output undefined");
      }
    });
  };
})(jQuery);
