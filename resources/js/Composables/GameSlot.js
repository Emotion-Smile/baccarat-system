export const useGroup = function (){
    return Object.freeze({
        DRAGON: 'DRAGON',
        DRAGON_COLOR: 'DRAGON_COLOR',
        TIGER: 'TIGER',
        TIGER_COLOR: 'TIGER_COLOR',
        TIE: 'TIE'
    });
};

export const useType = function (){
    return Object.freeze({
        DRAGON: 'DRAGON',
        DRAGON_RED: 'DRAGON_RED',
        DRAGON_BLACK: 'DRAGON_BLACK',
        TIGER: 'TIGER',
        TIGER_RED: 'TIGER_RED',
        TIGER_BLACK: 'TIGER_BLACK',
        TIE: 'TIE'
    });
};

export const useResultType = function (){
    return Object.freeze({
        DRAGON: 'DRAGON',
        TIGER: 'TIGER',
        CANCELLED: 'CANCEL',
        TIE: 'TIE'
    });
};

