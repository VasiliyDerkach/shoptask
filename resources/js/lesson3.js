// const answer = prompt('сколько будет 2 + 2?')
answer = 99
switch (answer) {
    case '4': {
        alert('правильно!')
        break
    }
    case '3': {
        alert('больше!')
        break
    }
    case '5': {
        alert('меньше')
        break
    }
    // default: {
    //     alert('неправильно!')
    //     break
    // }
}

function sayHello () {
    str = 'Hello, World!'
    console.log('внутри функции', str)
}

function sum(a, b = 0) {
    console.log(a + b)
}

function sum2(a, b) {
    if (b === undefined) {
        b = 0
    }
    console.log(a + b)
}

function noReturn () {
    console.log('вызвали функции noReturn')
}

function fullName (firstName, lastName) {
    return firstName + ' ' + lastName
}

let sayHelloWorld = function () {
    console.log('Hello, world!!!')
}

let str = 'Hello!'

sayHello()

console.log('после функции', str)

let a = 10
let b = 15

sum(1)

sum2(5)

let res = noReturn()

console.log('res = ', res)

let myName = fullName('Rail', 'Mingaliev')
console.log('name = ', myName)

console.log('sayHelloWorld', sayHelloWorld)

sayHelloWorld()

function callBackExample (access, accept, decline) {
    if (access) {
        accept()
    } else {
        decline()
    }
}

console.log('callBackExample', callBackExample)

// const accept = function () {
//     alert('доступ предоставлен!')
// }

// const decline = function () {
//     alert('доступ запрещен')
// }

function accept () {
    alert('доступ предоставлен!')
}

function decline () {
    alert('доступ запрещен!')
}

callBackExample(false, accept, decline)

let arrowFunc = (a, b, c) => a + b + c

console.log(arrowFunc(1, 2, 3))

arrowFunc = function (a, b, c) {
    return a + b + c
}

console.log(arrowFunc(4, 5, 6))

let newArrowFunc = (a, b) => {
    console.log('запустили стрелочную функцию newArrowFunc')
    return a + b
}

console.log(newArrowFunc(4, 6))

let user = {
    name: 'Rail',
    age: 28
}

console.log(user)

console.log('user.name', user.name)

user.age = 29
console.log(user)

delete user.age

console.log(user)

user.age = 28

console.log(user)

for (key in user) {
    console.log(user[key])
}

console.log('name' in user)
console.log('car' in user)