import React from 'react'
import { Button } from '../components/ui/button'

interface HelloProps {
    fullName: string;
}

export default function (props: HelloProps) {
    const handleClick = () => {
        console.log('foo');
    };

    return (
        <div className="font-bold">
            Hello { props.fullName }
            <Button variant="outline" onClick={handleClick}>Test</Button>
        </div>
    )
}
