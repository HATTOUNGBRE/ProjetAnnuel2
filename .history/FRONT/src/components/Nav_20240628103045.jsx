import { Link } from "react-router-dom";

function Nav(props) {
    const { isLoggedIn, userRole } = props;

    console.log('isLoggedIn page NAV:', isLoggedIn);
    return (
        <>
            <div className='flex space-x-8'>
                <Link to={`/${userRole}/Reservation`}>
                    <button className="bg-pcs-250 hover:bg-pcs-300 text-white font-bold py-2 px-8 rounded">
                        Demande de Prestation
                    </button>
                </Link>

                <Link to={`/components/dashboard/${userRole}`}>
                    <button className="bg-pcs-250 hover:bg-pcs-300 text-white font-bold py-2 px-8 rounded">
                        Dashboard
                    </button>
                </Link>

                <Link to={`/${userRole}/Prestation`}>
                    <button className="bg-pcs-250 hover:bg-pcs-300 text-white font-bold py-2 px-8 rounded">
                        Prestations
                    </button>
                </Link>
            </div>
            {isLoggedIn ? (
                <div className='flex space-x-8'>
                    <Link to='/account'>
                        <button className="bg-pcs-400 hover:bg-pcs-700 text-white font-bold py-2 px-8 rounded">
                            Account
                        </button>
                    </Link>
                    <Link to='/logout'>
                        <button className="bg-pcs-300 hover:bg-pcs-600 text-white font-bold py-2 px-8 rounded">
                            Log out
                        </button>
                    </Link>
                    
                </div>
            ) : (
                <div className='flex space-x-8'>
                    <Link to='/components/catuser'>
                        <button className="bg-pcs-400 hover:bg-pcs-700 text-white font-bold py-2 px-8 rounded">
                            Log In
                        </button>
                    </Link>
                    
                    <Link to='/components/Inscription'>
                        <button className="bg-pcs-300 hover:bg-pcs-600 text-white font-bold py-2 px-8 rounded">
                            Sign Up
                        </button>
                    </Link>
                </div>
            )}
        </>
    );
};

export default Nav;
