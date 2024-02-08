export function useTranslate() {

    function __(key, replacements = {}) {

        if (key === undefined) return 'undefined';

        key = key.toLowerCase();
        let translation = window._translations[key] || key;

        Object.keys(replacements).forEach(r => {
            translation = translation.replace(`:${r}`, replacements[r]);
        });

        return translation;
    }

    return {
        __
    }
}
