import React from 'react'

interface HelloProps {
    fullName: string;
}

export default function (props: HelloProps) {
    return <div className="font-bold">Hello {props.fullName}</div>;
}
