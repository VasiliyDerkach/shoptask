// сравнения


let x = 10
let y = 5

let res = x > y

console.log('x == 10', x == 10)


let b = 'b'
let a = 'a'

console.log('b > a', b > a)

let str1 = 'abc'
let str2 = 'abbb'

console.log('str1 > str2', str1 > str2)

console.log("'1' == 1", '1' == 1)
console.log("'1' === 1", '1' === 1)

let variable = '0'
let variable1 = 0

console.log(Boolean(variable), Boolean(variable1))
console.log('variable == variable1', variable == variable1)

console.log('null == undefined', null == undefined)

console.log('null > 0', null > 0)
console.log('null == 0', null == 0)
console.log('null >= 0', null >= 0)

console.log('undefined > 0', undefined > 0)
console.log('undefined == 0', undefined == 0)
console.log('undefined >= 0', undefined >= 0)

// условные конструкции

let answer = prompt('какой год сейчас?')

if (answer == 2022) {
    alert('правильно!')
} else if (answer < 2022) {
    alert('больше!')
} else {
    alert('вы что, из будущего?')
}

let age = 28

let access = age > 18 ? 'доступ есть' : 'доступа нет'
console.log('access', access)

// && || 

let haveLicence = true
let number1 = 123
let string1 = ''

res = string1 || number1 || haveLicence

if (res) {
    console.log('yes')
} else {
    console.log('no')
}

res = string1 && number1 && haveLicence

if (res) {
    console.log('yes')
} else {
    console.log('no')
}

console.log('res = ', res)

// циклы
let i = 0
while (i < 10) {
    console.log(i++)
}

let j = 0
for (j = 0; j < 5; j++) {
    if (j == 2) {
        continue
    }
    console.log(j)
}

console.log('j', j)