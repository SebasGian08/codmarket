(function () {
    var titleSelector = [
        '.section_heading_title small',
        '.sec-titulo',
        '.sec-title_three-heading',
        '.sec-title_two-heading',
        '.title_text',
        '.section-heading-center h2',
        '.section-heading-center h3',
        '.mvv-heading'
    ].join(',');

    function findLastTextNode(element) {
        var walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
        var lastNode = null;
        var node;

        while ((node = walker.nextNode())) {
            if (node.nodeValue.trim()) {
                lastNode = node;
            }
        }

        return lastNode;
    }

    function underlineLastWord(element) {
        var textNode = findLastTextNode(element);

        if (!textNode) {
            return;
        }

        var text = textNode.nodeValue;
        var match = text.match(/(\S+)(\s*)$/u);

        if (!match || textNode.parentElement.closest('.heading-last-word')) {
            return;
        }

        var wordStart = match.index;
        var word = document.createElement('span');
        word.className = 'heading-last-word';
        word.textContent = match[1];

        var fragment = document.createDocumentFragment();
        fragment.appendChild(document.createTextNode(text.slice(0, wordStart)));
        fragment.appendChild(word);
        fragment.appendChild(document.createTextNode(match[2]));
        textNode.parentNode.replaceChild(fragment, textNode);
    }

    function applyTitleAccents() {
        document.querySelectorAll(titleSelector).forEach(underlineLastWord);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyTitleAccents, { once: true });
    } else {
        applyTitleAccents();
    }
})();
