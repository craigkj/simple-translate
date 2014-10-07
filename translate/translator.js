var JSTranslator = function() {

    var strings = {};

    this.setup = function(translations){
       strings = translations;
    }

    this.translate = function(key, params, number) {
        // Handle undefined keys
        if (typeof strings[key] == 'undefined') {

            return key;
        }

        var string = strings[key];
        string = getPluralFromNumber(string, number);

        if (typeof params == 'object') {
            for (var property in params) {
                string = string.replace(':' + property + ':', params[property]);
            }
        }

        return string;
    };

    /*
     * Get the translation value string if a plural is identified and work out which form is required
     */
    var getPluralFromNumber = function(string, number) {
        if (typeof number == 'undefined') {
            return string;
        }

        /*
         * Check if the translation file array is pluralised
         * e.g. 'x_apples' => '{0} There are no apples||{1} There is one apple||{2,Inf} There are :count: apples'
         */
        number = parseInt(number);
        var pattern = new RegExp(/^\{(\d+)(?:,\s?(\d+|Inf))?\}/i);

        if (pattern.test(string)) {
            var pluralPieces = string.split('||');

            // check each possible 'plural' format ('are no','is one','are x')
            for (var i=0;i<pluralPieces.length;i++) {

                var pluralPiece = pluralPieces[i];
                var matches = pluralPiece.match(/^\{(\d+)(?:,\s?(\d+|Inf))?\}/i);

                if (matches.length > 0) {
                    var regexLength = matches[0].length;
                    var lower = matches[1].replace('{','');

                    // look for second option if it exists, if not make upper equal to lower
                    if (typeof matches[2] == 'undefined') {
                        var upper = matches[1].replace('}','');
                    } else {
                        var upper = matches[2].replace('}','');
                    }

                    // cater for infinity. In this case we only care about being >= lower bound
                    if (upper == 'Inf') {
                        if(parseInt(lower) <= number){
                            string = pluralPiece.substring(regexLength);
                        }
                    // Compare value with upper and lower limit, if match found then tidy up and exit.
                    } else if (parseInt(lower) <= number && parseInt(upper) >= number) {
                            string = pluralPiece.substring(regexLength);
                    }
                }
            }
        }
        return string;
    };

};
