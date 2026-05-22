export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    role?: RoleLike;
    roles?: RoleLike[];
    permissions?: RoleLike[];
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type RoleLike = string | { name?: string; label?: string; key?: string };

/* @chisel-passkeys */
export type Passkey = {
    id: number;
    name: string;
    authenticator: string | null;
    created_at_diff: string;
    last_used_at_diff: string | null;
};
/* @end-chisel-passkeys */
