addEventListener('DOMContentLoaded', (evt) => {

    const ctmPreviewColors = document.querySelectorAll('' +
        '[id^=ctrl_styleManager][id$=hlTextColor],' +
        '[id^=ctrl_styleManager][id$=textColor],' +
        '[id^=ctrl_styleManager][id$=templateTextColor],' +
        '[id^=ctrl_styleManager][id$=Background_color]'
    );

    ctmPreviewColors.forEach((group) => {
        addCTMBackendColors(group);
    });
});

function addCTMBackendColors(smSelect)
{
    let options = smSelect.options;
    let sibling = smSelect.parentElement.querySelector('.chzn-results');

    if (!!sibling)
    {
        for (let i = 0; i<options.length; i++)
        {
            if (!!options[i].value)
            {
                let option = sibling.childNodes[i];
                option.classList.add(options[i].value, 'preview');
            }
        }
    }
}
