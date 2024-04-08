<?php

namespace App\Enum;

class LineEventType
{
    const MESSAGE = 'message';
    const POSTBACK = 'postback';
    const FOLLOW = 'follow';
    const UNFOLLOW = 'unfollow';
    const JOIN = 'join';
    const LEAVE = 'leave';
    const MEMBER_JOINED = 'memberJoined';
    const MEMBER_LEFT = 'memberLeft';
    const BEACON = 'beacon';
    const ACCOUNT_LINK = 'accountLink';
    const THINGS = 'things';
}